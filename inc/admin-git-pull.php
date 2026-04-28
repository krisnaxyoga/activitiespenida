<?php
/**
 * Theme Git Pull - Admin Only
 *
 * Allows administrators to pull latest theme updates from Git.
 * Supports two modes:
 * - exec() mode: runs git pull via shell (when exec is enabled)
 * - HTTP mode: downloads zip from GitHub/GitLab API (when exec is disabled)
 *
 * Auto-detects provider (GitHub or GitLab) from repository URL.
 */

if (!defined('ABSPATH')) {
    exit;
}

class Theme_Git_Pull {

    private const NONCE_ACTION      = 'theme_git_pull_action';
    private const NONCE_NAME        = 'theme_git_pull_nonce';
    private const PUSH_NONCE_ACTION = 'theme_git_push_action';
    private const PUSH_NONCE_NAME   = 'theme_git_push_nonce';
    private const SETTINGS_NONCE    = 'theme_git_pull_settings_nonce';
    private const SETTINGS_ACTION   = 'theme_git_pull_save_settings';
    private const CAPABILITY        = 'manage_options';
    private const MENU_SLUG         = 'theme-git-pull';
    private const BRANCH            = 'main';
    private const OPTION_REPO_URL   = 'theme_git_pull_gitlab_url';   // kept for backward compat
    private const OPTION_REPO_TOKEN = 'theme_git_pull_gitlab_token'; // kept for backward compat

    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'handle_requests']);
    }

    public function add_admin_menu(): void {
        add_theme_page(
            'Theme Git Pull',
            'Git Pull',
            self::CAPABILITY,
            self::MENU_SLUG,
            [$this, 'render_admin_page']
        );
    }

    public function handle_requests(): void {
        if (!current_user_can(self::CAPABILITY)) {
            return;
        }

        // Handle settings save
        if (isset($_POST[self::SETTINGS_NONCE])) {
            if (!wp_verify_nonce($_POST[self::SETTINGS_NONCE], self::SETTINGS_ACTION)) {
                wp_die('Security check failed.', 'Error', ['response' => 403]);
            }

            $repo_url = isset($_POST['repo_url']) ? esc_url_raw(trim($_POST['repo_url'])) : '';
            update_option(self::OPTION_REPO_URL, $repo_url);

            if (!empty($_POST['repo_token'])) {
                update_option(self::OPTION_REPO_TOKEN, sanitize_text_field($_POST['repo_token']));
            }

            set_transient('theme_git_pull_result', [
                'success' => true,
                'output'  => 'Settings saved successfully.',
            ], 60);

            wp_safe_redirect(admin_url('themes.php?page=' . self::MENU_SLUG . '&saved=1'));
            exit;
        }

        // Handle push request (manual)
        if (isset($_POST[self::PUSH_NONCE_NAME])) {
            if (!wp_verify_nonce($_POST[self::PUSH_NONCE_NAME], self::PUSH_NONCE_ACTION)) {
                wp_die('Security check failed.', 'Error', ['response' => 403]);
            }

            $result = $this->execute_commit_and_push();

            set_transient('theme_git_pull_result', $result, 60);

            wp_safe_redirect(admin_url('themes.php?page=' . self::MENU_SLUG . '&pushed=1'));
            exit;
        }

        // Handle pull request
        if (isset($_POST[self::NONCE_NAME])) {
            if (!wp_verify_nonce($_POST[self::NONCE_NAME], self::NONCE_ACTION)) {
                wp_die('Security check failed.', 'Error', ['response' => 403]);
            }

            $result = $this->execute_pull();

            set_transient('theme_git_pull_result', $result, 60);

            wp_safe_redirect(admin_url('themes.php?page=' . self::MENU_SLUG . '&pulled=1'));
            exit;
        }
    }

    // =====================================================================
    // PROVIDER DETECTION
    // =====================================================================

    /**
     * Detect git provider from URL.
     * @return string 'github' | 'gitlab' | 'unknown'
     */
    private function detect_provider(string $url): string {
        $host = parse_url($url, PHP_URL_HOST);
        if (empty($host)) {
            return 'unknown';
        }
        $host = strtolower($host);

        if ($host === 'github.com' || str_ends_with($host, '.github.com')) {
            return 'github';
        }
        if ($host === 'gitlab.com' || str_ends_with($host, '.gitlab.com')) {
            return 'gitlab';
        }

        return 'unknown';
    }

    private function get_provider_label(): string {
        $url = get_option(self::OPTION_REPO_URL, '');
        $provider = $this->detect_provider($url);
        return match ($provider) {
            'github' => 'GitHub',
            'gitlab' => 'GitLab',
            default  => 'Git',
        };
    }

    // =====================================================================
    // EXEC DETECTION
    // =====================================================================

    private function can_exec(): bool {
        return function_exists('exec') && !$this->is_exec_disabled();
    }

    private function is_exec_disabled(): bool {
        $disabled = explode(',', ini_get('disable_functions'));
        $disabled = array_map('trim', $disabled);
        return in_array('exec', $disabled);
    }

    // =====================================================================
    // PULL EXECUTION
    // =====================================================================

    private function execute_pull(): array {
        if ($this->can_exec()) {
            return $this->execute_git_pull_exec();
        }

        return $this->execute_git_pull_http();
    }

    /**
     * exec-based git pull (works with any provider).
     * Auto-commits and pushes local changes before pulling.
     */
    private function execute_git_pull_exec(): array {
        $theme_dir = get_template_directory();

        if (!is_dir($theme_dir . '/.git')) {
            return [
                'success' => false,
                'output'  => 'Error: Theme directory is not a Git repository.',
            ];
        }

        $log = '';

        // Auto commit+push local changes before pulling
        if ($this->has_local_changes()) {
            $log .= "--- Local changes detected, auto-committing... ---\n\n";
            $push_result = $this->execute_commit_and_push();
            $log .= $push_result['output'] . "\n";

            if (!$push_result['success']) {
                $log .= "\n--- Auto push failed. Aborting pull. ---\n";
                return [
                    'success' => false,
                    'output'  => $log,
                ];
            }

            $log .= "\n--- Auto push complete. Proceeding with pull... ---\n\n";
        }

        $safe_dir    = escapeshellarg($theme_dir);
        $safe_branch = escapeshellarg(self::BRANCH);

        $command = sprintf(
            'cd %s && git pull origin %s 2>&1',
            $safe_dir,
            $safe_branch
        );

        $output      = [];
        $return_code = 0;

        exec($command, $output, $return_code);

        $log .= implode("\n", $output);

        return [
            'success' => $return_code === 0,
            'output'  => $log,
            'code'    => $return_code,
        ];
    }

    // =====================================================================
    // LOCAL CHANGES DETECTION & AUTO COMMIT+PUSH
    // =====================================================================

    /**
     * Check if there are uncommitted local changes.
     */
    private function has_local_changes(): bool {
        if (!$this->can_exec()) {
            return false;
        }

        $theme_dir = get_template_directory();
        $safe_dir  = escapeshellarg($theme_dir);

        $output      = [];
        $return_code = 0;
        exec("cd {$safe_dir} && git status --porcelain 2>&1", $output, $return_code);

        return $return_code === 0 && !empty($output);
    }

    /**
     * Get list of changed files for display.
     */
    private function get_local_changes(): string {
        $theme_dir = get_template_directory();
        $safe_dir  = escapeshellarg($theme_dir);

        $output = [];
        exec("cd {$safe_dir} && git status --short 2>&1", $output);

        return implode("\n", $output);
    }

    /**
     * Execute git add, commit, and push for local server changes.
     */
    private function execute_commit_and_push(string $message = ''): array {
        if (!$this->can_exec()) {
            return [
                'success' => false,
                'output'  => 'Error: exec() is not available. Push requires shell access.',
            ];
        }

        $theme_dir   = get_template_directory();
        $safe_dir    = escapeshellarg($theme_dir);
        $safe_branch = escapeshellarg(self::BRANCH);

        if (!is_dir($theme_dir . '/.git')) {
            return [
                'success' => false,
                'output'  => 'Error: Theme directory is not a Git repository.',
            ];
        }

        // Check for changes first
        $status_output = [];
        exec("cd {$safe_dir} && git status --porcelain 2>&1", $status_output, $status_code);

        if ($status_code !== 0) {
            return [
                'success' => false,
                'output'  => "Error: git status failed.\n" . implode("\n", $status_output),
            ];
        }

        if (empty($status_output)) {
            return [
                'success' => true,
                'output'  => 'No local changes detected. Nothing to push.',
            ];
        }

        $log  = "Detected " . count($status_output) . " changed file(s):\n";
        $log .= implode("\n", $status_output) . "\n\n";

        // git add .
        $add_output = [];
        exec("cd {$safe_dir} && git add . 2>&1", $add_output, $add_code);
        $log .= "git add . → " . ($add_code === 0 ? 'OK' : 'FAILED') . "\n";

        if ($add_code !== 0) {
            return [
                'success' => false,
                'output'  => $log . implode("\n", $add_output),
            ];
        }

        // git commit
        if (empty($message)) {
            $message = 'Update from server — ' . date('Y-m-d H:i:s');
        }
        $safe_msg = escapeshellarg($message);
        $commit_output = [];
        exec("cd {$safe_dir} && git commit -m {$safe_msg} 2>&1", $commit_output, $commit_code);
        $log .= "git commit → " . ($commit_code === 0 ? 'OK' : 'FAILED') . "\n";
        $log .= implode("\n", $commit_output) . "\n\n";

        if ($commit_code !== 0) {
            return [
                'success' => false,
                'output'  => $log,
            ];
        }

        // git push origin main
        $push_output = [];
        exec("cd {$safe_dir} && git push origin {$safe_branch} 2>&1", $push_output, $push_code);
        $log .= "git push origin " . self::BRANCH . " → " . ($push_code === 0 ? 'OK' : 'FAILED') . "\n";
        $log .= implode("\n", $push_output) . "\n";

        return [
            'success' => $push_code === 0,
            'output'  => $log,
        ];
    }

    /**
     * HTTP-based pull: route to the correct provider.
     */
    private function execute_git_pull_http(): array {
        $repo_url = get_option(self::OPTION_REPO_URL, '');
        $token    = get_option(self::OPTION_REPO_TOKEN, '');

        if (empty($repo_url)) {
            return [
                'success' => false,
                'output'  => 'Error: Repository URL is not configured. Please save your settings first.',
            ];
        }

        if (empty($token)) {
            return [
                'success' => false,
                'output'  => 'Error: Access Token is not configured. Required for private repos.',
            ];
        }

        if (!class_exists('ZipArchive')) {
            return [
                'success' => false,
                'output'  => 'Error: PHP ZipArchive extension is not available.',
            ];
        }

        $provider = $this->detect_provider($repo_url);

        return match ($provider) {
            'github' => $this->execute_github_pull($repo_url, $token),
            'gitlab' => $this->execute_gitlab_pull($repo_url, $token),
            default  => [
                'success' => false,
                'output'  => 'Error: Could not detect provider from URL. Supported: GitHub, GitLab.',
            ],
        };
    }

    // =====================================================================
    // GITHUB HTTP PULL
    // =====================================================================

    private function execute_github_pull(string $repo_url, string $token): array {
        $repo_path = $this->get_github_repo_path($repo_url);
        if (empty($repo_path)) {
            return [
                'success' => false,
                'output'  => 'Error: Could not parse GitHub repo path from URL: ' . $repo_url,
            ];
        }

        $api_url = "https://api.github.com/repos/{$repo_path}/zipball/" . self::BRANCH;

        $log  = "Downloading from GitHub API...\n";
        $log .= "Repository: {$repo_path}\n";
        $log .= "Branch: " . self::BRANCH . "\n\n";

        $response = wp_remote_get($api_url, [
            'timeout' => 120,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/vnd.github+json',
                'User-Agent'    => 'WordPress/' . get_bloginfo('version'),
            ],
        ]);

        return $this->process_zip_response($response, $log);
    }

    /**
     * Extract GitHub owner/repo from URL.
     * e.g., "https://github.com/owner/repo" => "owner/repo"
     */
    private function get_github_repo_path(string $url): string {
        $url = rtrim($url, '/');
        $url = preg_replace('/\.git$/', '', $url);
        $parsed = parse_url($url);
        if (empty($parsed['path'])) {
            return '';
        }

        $path = ltrim($parsed['path'], '/');
        $parts = explode('/', $path);

        // Need at least owner/repo
        if (count($parts) < 2) {
            return '';
        }

        return $parts[0] . '/' . $parts[1];
    }

    // =====================================================================
    // GITLAB HTTP PULL
    // =====================================================================

    private function execute_gitlab_pull(string $repo_url, string $token): array {
        $project_path = $this->get_gitlab_project_path($repo_url);
        if (empty($project_path)) {
            return [
                'success' => false,
                'output'  => 'Error: Could not parse GitLab project path from URL: ' . $repo_url,
            ];
        }

        $encoded_path = rawurlencode($project_path);
        $api_url      = "https://gitlab.com/api/v4/projects/{$encoded_path}/repository/archive.zip?sha=" . self::BRANCH;

        $log  = "Downloading from GitLab API...\n";
        $log .= "Project: {$project_path}\n";
        $log .= "Branch: " . self::BRANCH . "\n\n";

        $response = wp_remote_get($api_url, [
            'timeout' => 120,
            'headers' => [
                'PRIVATE-TOKEN' => $token,
            ],
        ]);

        return $this->process_zip_response($response, $log);
    }

    /**
     * Extract GitLab project path from URL.
     * e.g., "https://gitlab.com/group/subgroup/repo" => "group/subgroup/repo"
     */
    private function get_gitlab_project_path(string $url): string {
        $url = rtrim($url, '/');
        $url = preg_replace('/\.git$/', '', $url);
        $parsed = parse_url($url);
        if (empty($parsed['path'])) {
            return '';
        }
        return ltrim($parsed['path'], '/');
    }

    // =====================================================================
    // SHARED ZIP PROCESSING
    // =====================================================================

    /**
     * Process the HTTP response: validate, extract zip, copy to theme dir.
     */
    private function process_zip_response($response, string $log): array {
        if (is_wp_error($response)) {
            return [
                'success' => false,
                'output'  => $log . 'Download failed: ' . $response->get_error_message(),
            ];
        }

        $status_code = wp_remote_retrieve_response_code($response);
        if ($status_code !== 200) {
            $body = wp_remote_retrieve_body($response);
            return [
                'success' => false,
                'output'  => $log . "Download failed with HTTP {$status_code}.\n" . substr($body, 0, 500),
            ];
        }

        $zip_content = wp_remote_retrieve_body($response);
        if (empty($zip_content)) {
            return [
                'success' => false,
                'output'  => $log . 'Error: Downloaded file is empty.',
            ];
        }

        $log .= "Downloaded " . size_format(strlen($zip_content)) . "\n";

        // Save to temp file
        $tmp_dir     = get_temp_dir();
        $tmp_zip     = $tmp_dir . 'theme-git-pull-' . wp_generate_password(8, false) . '.zip';
        $tmp_extract = $tmp_dir . 'theme-git-pull-extract-' . wp_generate_password(8, false);

        $written = file_put_contents($tmp_zip, $zip_content);
        unset($zip_content);

        if ($written === false) {
            return [
                'success' => false,
                'output'  => $log . 'Error: Could not write temporary zip file.',
            ];
        }

        // Extract zip
        $zip = new ZipArchive();
        $open_result = $zip->open($tmp_zip);

        if ($open_result !== true) {
            @unlink($tmp_zip);
            return [
                'success' => false,
                'output'  => $log . 'Error: Could not open zip file (code: ' . $open_result . ').',
            ];
        }

        wp_mkdir_p($tmp_extract);
        $zip->extractTo($tmp_extract);
        $zip->close();
        @unlink($tmp_zip);

        // Find the extracted subdirectory
        $extracted_dirs = glob($tmp_extract . '/*', GLOB_ONLYDIR);
        if (empty($extracted_dirs)) {
            $this->recursive_delete($tmp_extract);
            return [
                'success' => false,
                'output'  => $log . 'Error: No directory found in extracted zip.',
            ];
        }

        $source_dir = $extracted_dirs[0];
        $theme_dir  = get_template_directory();

        $file_count = 0;
        $this->count_files($source_dir, $file_count);
        $log .= "Extracted {$file_count} files\n";
        $log .= "Updating theme directory...\n\n";

        $copy_result = $this->recursive_copy($source_dir, $theme_dir);

        // Cleanup
        $this->recursive_delete($tmp_extract);

        if ($copy_result['errors']) {
            $log .= "Completed with errors:\n" . implode("\n", $copy_result['errors']);
            return [
                'success' => false,
                'output'  => $log,
            ];
        }

        $log .= "Successfully updated {$copy_result['copied']} files.";

        return [
            'success' => true,
            'output'  => $log,
        ];
    }

    // =====================================================================
    // FILE OPERATIONS
    // =====================================================================

    private function recursive_copy(string $src, string $dst): array {
        $result = ['copied' => 0, 'errors' => []];
        $dir    = opendir($src);

        if ($dir === false) {
            $result['errors'][] = "Could not open directory: {$src}";
            return $result;
        }

        wp_mkdir_p($dst);

        while (($file = readdir($dir)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $src_path = $src . '/' . $file;
            $dst_path = $dst . '/' . $file;

            if ($file === '.git') {
                continue;
            }

            if (is_dir($src_path)) {
                $sub_result = $this->recursive_copy($src_path, $dst_path);
                $result['copied'] += $sub_result['copied'];
                $result['errors'] = array_merge($result['errors'], $sub_result['errors']);
            } else {
                if (copy($src_path, $dst_path)) {
                    $result['copied']++;
                } else {
                    $result['errors'][] = "Failed to copy: {$file}";
                }
            }
        }

        closedir($dir);
        return $result;
    }

    private function recursive_delete(string $dir): void {
        if (!is_dir($dir)) {
            return;
        }

        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $this->recursive_delete($path);
            } else {
                @unlink($path);
            }
        }

        @rmdir($dir);
    }

    private function count_files(string $dir, int &$count): void {
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $this->count_files($path, $count);
            } else {
                $count++;
            }
        }
    }

    // =====================================================================
    // DIAGNOSTICS
    // =====================================================================

    private function get_diagnostics(): array {
        $theme_dir  = get_template_directory();
        $exec_ok    = $this->can_exec();
        $repo_url   = get_option(self::OPTION_REPO_URL, '');
        $repo_token = get_option(self::OPTION_REPO_TOKEN, '');
        $provider   = $this->detect_provider($repo_url);
        $diagnostics = [];

        // 1. Pull method
        $diagnostics['pull_method'] = [
            'label'  => 'Pull Method',
            'status' => true,
            'value'  => $exec_ok ? 'exec() - Git CLI' : 'HTTP - API Download',
            'help'   => $exec_ok ? 'Using native git commands' : 'exec() disabled, using HTTP fallback',
        ];

        if ($exec_ok) {
            // --- exec() mode diagnostics ---

            $git_version = '';
            $git_output  = [];
            exec('git --version 2>&1', $git_output, $git_code);
            $git_version = $git_code === 0 ? implode('', $git_output) : 'Not found';
            $diagnostics['git_installed'] = [
                'label'  => 'Git Installation',
                'status' => strpos($git_version, 'git version') !== false,
                'value'  => $git_version,
                'help'   => 'Git must be installed on the server',
            ];

            $git_dir_exists = is_dir($theme_dir . '/.git');
            $diagnostics['git_directory'] = [
                'label'  => 'Git Repository (.git)',
                'status' => $git_dir_exists,
                'value'  => $git_dir_exists ? 'Found' : 'Not found',
                'help'   => 'Theme directory must be a git repository',
            ];

            $remote_url    = '';
            $remote_output = [];
            if ($git_dir_exists) {
                $safe_dir = escapeshellarg($theme_dir);
                exec("cd {$safe_dir} && git remote get-url origin 2>&1", $remote_output, $remote_code);
                $remote_url = $remote_code === 0 ? implode('', $remote_output) : 'Not configured';
            }
            $diagnostics['git_remote'] = [
                'label'  => 'Git Remote (origin)',
                'status' => !empty($remote_url) && $remote_url !== 'Not configured',
                'value'  => $remote_url ?: 'Cannot check',
                'help'   => 'Remote repository URL',
            ];

            $current_branch = '';
            $branch_output  = [];
            if ($git_dir_exists) {
                $safe_dir = escapeshellarg($theme_dir);
                exec("cd {$safe_dir} && git branch --show-current 2>&1", $branch_output, $branch_code);
                $current_branch = $branch_code === 0 ? implode('', $branch_output) : 'Unknown';
            }
            $diagnostics['current_branch'] = [
                'label'  => 'Current Branch',
                'status' => $current_branch === self::BRANCH,
                'value'  => $current_branch ?: 'Cannot check',
                'help'   => 'Should match target branch: ' . self::BRANCH,
            ];

        } else {
            // --- HTTP mode diagnostics ---

            $provider_label = match ($provider) {
                'github' => 'GitHub',
                'gitlab' => 'GitLab',
                default  => 'Unknown',
            };

            $diagnostics['provider'] = [
                'label'  => 'Detected Provider',
                'status' => $provider !== 'unknown',
                'value'  => !empty($repo_url) ? $provider_label : 'Not configured',
                'help'   => 'Supported: GitHub, GitLab',
            ];

            $diagnostics['repo_url'] = [
                'label'  => 'Repository URL',
                'status' => !empty($repo_url),
                'value'  => !empty($repo_url) ? $repo_url : 'Not configured',
                'help'   => 'Set in settings below',
            ];

            $diagnostics['repo_token'] = [
                'label'  => 'Access Token',
                'status' => !empty($repo_token),
                'value'  => !empty($repo_token) ? str_repeat('*', 8) . substr($repo_token, -4) : 'Not configured',
                'help'   => $provider === 'github'
                    ? 'GitHub Personal Access Token (classic) with repo scope'
                    : 'GitLab Personal Access Token with read_api scope',
            ];

            $zip_ok = class_exists('ZipArchive');
            $diagnostics['zip_archive'] = [
                'label'  => 'PHP ZipArchive',
                'status' => $zip_ok,
                'value'  => $zip_ok ? 'Available' : 'Not available',
                'help'   => 'Required to extract downloaded archive',
            ];

            // Test API connectivity
            $api_ok    = false;
            $api_value = 'Not tested';
            if (!empty($repo_url) && !empty($repo_token) && $provider !== 'unknown') {
                if ($provider === 'github') {
                    $repo_path = $this->get_github_repo_path($repo_url);
                    if (!empty($repo_path)) {
                        $test_url = "https://api.github.com/repos/{$repo_path}";
                        $test_response = wp_remote_get($test_url, [
                            'timeout' => 15,
                            'headers' => [
                                'Authorization' => 'Bearer ' . $repo_token,
                                'Accept'        => 'application/vnd.github+json',
                                'User-Agent'    => 'WordPress/' . get_bloginfo('version'),
                            ],
                        ]);

                        if (is_wp_error($test_response)) {
                            $api_value = 'Failed: ' . $test_response->get_error_message();
                        } else {
                            $status = wp_remote_retrieve_response_code($test_response);
                            if ($status === 200) {
                                $body = json_decode(wp_remote_retrieve_body($test_response), true);
                                $api_ok    = true;
                                $api_value = 'Connected - ' . ($body['full_name'] ?? $repo_path);
                            } elseif ($status === 401) {
                                $api_value = 'Invalid token (HTTP 401)';
                            } elseif ($status === 404) {
                                $api_value = 'Repo not found (HTTP 404) - check URL and token scope';
                            } else {
                                $api_value = "HTTP {$status}";
                            }
                        }
                    } else {
                        $api_value = 'Could not parse repo path from URL';
                    }

                } else {
                    // GitLab
                    $project_path = $this->get_gitlab_project_path($repo_url);
                    if (!empty($project_path)) {
                        $encoded_path = rawurlencode($project_path);
                        $test_url = "https://gitlab.com/api/v4/projects/{$encoded_path}";
                        $test_response = wp_remote_get($test_url, [
                            'timeout' => 15,
                            'headers' => ['PRIVATE-TOKEN' => $repo_token],
                        ]);

                        if (is_wp_error($test_response)) {
                            $api_value = 'Failed: ' . $test_response->get_error_message();
                        } else {
                            $status = wp_remote_retrieve_response_code($test_response);
                            if ($status === 200) {
                                $body = json_decode(wp_remote_retrieve_body($test_response), true);
                                $api_ok    = true;
                                $api_value = 'Connected - ' . ($body['name_with_namespace'] ?? $project_path);
                            } elseif ($status === 401) {
                                $api_value = 'Invalid token (HTTP 401)';
                            } elseif ($status === 404) {
                                $api_value = 'Project not found (HTTP 404) - check URL and token scope';
                            } else {
                                $api_value = "HTTP {$status}";
                            }
                        }
                    } else {
                        $api_value = 'Could not parse project path from URL';
                    }
                }
            }
            $diagnostics['api_connection'] = [
                'label'  => $provider_label . ' API Connection',
                'status' => $api_ok,
                'value'  => $api_value,
                'help'   => 'Verifies token and repo access',
            ];
        }

        // Directory writable (both modes)
        $dir_writable = is_writable($theme_dir);
        $diagnostics['dir_permissions'] = [
            'label'  => 'Theme Directory Writable',
            'status' => $dir_writable,
            'value'  => $dir_writable ? 'Yes' : 'No',
            'help'   => 'Web server must have write permission',
        ];

        return $diagnostics;
    }

    private function is_pull_ready(array $diagnostics): bool {
        if ($this->can_exec()) {
            return ($diagnostics['git_installed']['status'] ?? false)
                && ($diagnostics['git_directory']['status'] ?? false)
                && ($diagnostics['git_remote']['status'] ?? false)
                && ($diagnostics['dir_permissions']['status'] ?? false);
        }

        // HTTP mode
        return ($diagnostics['repo_url']['status'] ?? false)
            && ($diagnostics['repo_token']['status'] ?? false)
            && ($diagnostics['zip_archive']['status'] ?? false)
            && ($diagnostics['api_connection']['status'] ?? false)
            && ($diagnostics['dir_permissions']['status'] ?? false);
    }

    // =====================================================================
    // ADMIN PAGE RENDER
    // =====================================================================

    public function render_admin_page(): void {
        if (!current_user_can(self::CAPABILITY)) {
            return;
        }

        $result      = get_transient('theme_git_pull_result');
        $diagnostics = $this->get_diagnostics();
        $all_ok      = $this->is_pull_ready($diagnostics);
        $is_http     = !$this->can_exec();

        if ($result) {
            delete_transient('theme_git_pull_result');
        }

        $repo_url   = get_option(self::OPTION_REPO_URL, '');
        $repo_token = get_option(self::OPTION_REPO_TOKEN, '');
        $provider   = $this->detect_provider($repo_url);
        $provider_label = $this->get_provider_label();

        ?>
        <div class="wrap">
            <h1>Theme Git Pull</h1>
            <p>Pull the latest theme updates from the Git repository. Supports <strong>GitHub</strong> and <strong>GitLab</strong>.</p>

            <?php if ($result): ?>
                <div class="notice <?php echo $result['success'] ? 'notice-success' : 'notice-error'; ?> is-dismissible">
                    <p><strong><?php echo $result['success'] ? 'Success!' : 'Error occurred.'; ?></strong></p>
                    <pre style="background:#f5f5f5;padding:10px;overflow-x:auto;max-width:100%;"><?php
                        echo esc_html($result['output']);
                    ?></pre>
                </div>
            <?php endif; ?>

            <!-- Diagnostics Section -->
            <h2>Server Diagnostics</h2>
            <table class="widefat striped" style="max-width:800px;">
                <thead>
                    <tr>
                        <th>Check</th>
                        <th>Status</th>
                        <th>Value</th>
                        <th>Info</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($diagnostics as $diag): ?>
                        <tr>
                            <td><strong><?php echo esc_html($diag['label']); ?></strong></td>
                            <td>
                                <?php if ($diag['status']): ?>
                                    <span style="color:green;">&#10004; OK</span>
                                <?php else: ?>
                                    <span style="color:red;">&#10008; FAIL</span>
                                <?php endif; ?>
                            </td>
                            <td><code style="word-break:break-all;"><?php echo esc_html($diag['value']); ?></code></td>
                            <td><em><?php echo esc_html($diag['help']); ?></em></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (!$all_ok && !$is_http): ?>
                <div class="notice notice-warning" style="max-width:780px;">
                    <p><strong>Warning:</strong> Some diagnostics failed. Git pull may not work correctly.</p>
                </div>
            <?php endif; ?>

            <?php
            // --- Local Changes & Push Section (exec mode only) ---
            if (!$is_http && $all_ok):
                $has_changes  = $this->has_local_changes();
                $changes_list = $has_changes ? $this->get_local_changes() : '';
            ?>
                <h2 style="margin-top:30px;">Server Changes</h2>
                <?php if ($has_changes): ?>
                    <div class="notice notice-warning" style="max-width:780px;">
                        <p><strong>Local changes detected!</strong> These files have been modified on the server:</p>
                    </div>
                    <pre style="background:#fff8e1;border:1px solid #ffe082;padding:12px;max-width:780px;overflow-x:auto;font-size:13px;line-height:1.6;"><?php echo esc_html($changes_list); ?></pre>

                    <form method="post" action="" style="margin-top:10px;">
                        <?php wp_nonce_field(self::PUSH_NONCE_ACTION, self::PUSH_NONCE_NAME); ?>
                        <p class="submit" style="margin-top:0;">
                            <button type="submit" class="button button-secondary"
                                    onclick="return confirm('This will commit all server changes and push to origin/<?php echo esc_attr(self::BRANCH); ?>. Continue?');"
                                    style="background:#2271b1;border-color:#2271b1;color:#fff;">
                                Push Server Changes to Git
                            </button>
                            <span class="description"> git add . &rarr; git commit &rarr; git push origin <?php echo esc_html(self::BRANCH); ?></span>
                        </p>
                    </form>
                <?php else: ?>
                    <p style="color:#46b450;"><span class="dashicons dashicons-yes-alt"></span> No local changes. Server is in sync with the repository.</p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($is_http): ?>
                <!-- Repository Settings Section -->
                <h2 style="margin-top:30px;">Repository Settings</h2>
                <p class="description">Since <code>exec()</code> is disabled on this server, theme updates are downloaded via the GitHub/GitLab API.</p>

                <form method="post" action="">
                    <?php wp_nonce_field(self::SETTINGS_ACTION, self::SETTINGS_NONCE); ?>

                    <table class="form-table" style="max-width:800px;">
                        <tr>
                            <th scope="row"><label for="repo_url">Repository URL</label></th>
                            <td>
                                <input type="url" id="repo_url" name="repo_url" class="regular-text"
                                       value="<?php echo esc_attr($repo_url); ?>"
                                       placeholder="https://github.com/owner/repo"
                                       style="width:100%;max-width:500px;">
                                <p class="description">
                                    Full URL to your repository.<br>
                                    GitHub: <code>https://github.com/owner/repo</code><br>
                                    GitLab: <code>https://gitlab.com/group/repo</code>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="repo_token">Personal Access Token</label></th>
                            <td>
                                <input type="password" id="repo_token" name="repo_token" class="regular-text"
                                       value="" placeholder="<?php echo !empty($repo_token) ? 'Token saved (enter new to replace)' : 'Enter token...'; ?>"
                                       style="width:100%;max-width:500px;">
                                <p class="description">
                                    <?php if ($provider === 'github'): ?>
                                        GitHub: Personal Access Token (classic) with <code>repo</code> scope.
                                        <a href="https://github.com/settings/tokens/new" target="_blank">Create one here</a>.
                                    <?php elseif ($provider === 'gitlab'): ?>
                                        GitLab: Personal Access Token with <code>read_api</code> scope.
                                        <a href="https://gitlab.com/-/user_settings/personal_access_tokens" target="_blank">Create one here</a>.
                                    <?php else: ?>
                                        Enter your GitHub or GitLab personal access token.
                                    <?php endif; ?>
                                    <?php if (!empty($repo_token)): ?>
                                        <br>Current token: <code><?php echo esc_html(str_repeat('*', 8) . substr($repo_token, -4)); ?></code>
                                    <?php endif; ?>
                                </p>
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <button type="submit" class="button button-secondary">Save Settings</button>
                    </p>
                </form>
            <?php endif; ?>

            <!-- Git Pull Section -->
            <h2 style="margin-top:30px;">Execute <?php echo $is_http ? 'Theme Update' : 'Git Pull'; ?></h2>
            <?php if (!$is_http): ?>
                <p class="description">If local changes are detected, they will be auto-committed and pushed before pulling.</p>
            <?php endif; ?>
            <form method="post" action="">
                <?php wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row">Theme Directory</th>
                        <td><code><?php echo esc_html(get_template_directory()); ?></code></td>
                    </tr>
                    <tr>
                        <th scope="row">Branch</th>
                        <td><code><?php echo esc_html(self::BRANCH); ?></code></td>
                    </tr>
                    <tr>
                        <th scope="row">Method</th>
                        <td><code><?php
                            if ($is_http) {
                                echo 'Download zip from ' . esc_html($provider_label) . ' API';
                            } else {
                                echo 'git pull origin ' . esc_html(self::BRANCH);
                            }
                        ?></code></td>
                    </tr>
                </table>

                <p class="submit">
                    <button type="submit" class="button button-primary"
                            <?php echo !$all_ok ? 'disabled' : ''; ?>
                            onclick="return confirm('Are you sure you want to pull the latest theme updates?');">
                        Pull Latest Theme Update
                    </button>
                    <?php if (!$all_ok): ?>
                        <span class="description" style="color:#d63638;">
                            <?php echo $is_http
                                ? ' Configure repository settings above first.'
                                : ' Button disabled due to failed diagnostics.'; ?>
                        </span>
                    <?php endif; ?>
                </p>
            </form>
        </div>
        <?php
    }
}

new Theme_Git_Pull();