<?php
/**
 * Penida Finder — CSV/JSON importer (drop-in port of the plugin importer).
 */

if (!defined('ABSPATH')) exit;

add_action('admin_menu', 'npf_add_import_menu');
function npf_add_import_menu() {
    add_submenu_page(
        'edit.php?post_type=business',
        'Import Businesses',
        'Import',
        'manage_options',
        'npf-import',
        'npf_import_page'
    );
}

function npf_import_page() {
    if (!current_user_can('manage_options')) return;
    ?>
    <div class="wrap">
        <h1>📥 Import Business Listings</h1>

        <div class="notice notice-info"><p><strong>Supported formats:</strong> CSV, JSON.</p></div>

        <div style="background:#fff;padding:20px;margin:20px 0;border:1px solid #ccc;border-radius:5px;">
            <h2>📄 Templates</h2>
            <p>
                <a href="<?php echo esc_url(admin_url('admin-ajax.php?action=npf_download_template&format=csv')); ?>" class="button">📊 CSV Template</a>
                <a href="<?php echo esc_url(admin_url('admin-ajax.php?action=npf_download_template&format=json')); ?>" class="button">📋 JSON Template</a>
            </p>
        </div>

        <div style="background:#fff;padding:20px;margin:20px 0;border:1px solid #ccc;border-radius:5px;">
            <h2>📤 Upload &amp; Import</h2>
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('npf_import_action', 'npf_import_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="import_file">File</label></th>
                        <td>
                            <input type="file" id="import_file" name="import_file" accept=".csv,.json" required>
                            <p class="description">Max 2MB.</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="import_mode">Mode</label></th>
                        <td>
                            <select id="import_mode" name="import_mode">
                                <option value="add">Add new only (skip existing)</option>
                                <option value="update">Update existing (by title)</option>
                                <option value="add_update">Add new &amp; update existing</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="default_category">Default category</label></th>
                        <td>
                            <?php wp_dropdown_categories([
                                'taxonomy'         => 'business_category',
                                'name'             => 'default_category',
                                'id'               => 'default_category',
                                'hide_empty'       => false,
                                'show_option_none' => '-- Select Default Category --',
                                'option_none_value' => '',
                            ]); ?>
                        </td>
                    </tr>
                </table>
                <p><input type="submit" name="npf_preview_import" class="button button-primary" value="Preview Import"></p>
            </form>
        </div>

        <?php
        if (isset($_POST['npf_preview_import']))  npf_handle_import_preview();
        if (isset($_POST['npf_confirm_import']))  npf_handle_import_confirm();
        if (isset($_POST['npf_flush_rewrite']) && check_admin_referer('npf_flush_action', 'npf_flush_nonce')) {
            flush_rewrite_rules();
            echo '<div class="notice notice-success"><p><strong>Permalinks refreshed.</strong></p></div>';
        }
        ?>

        <div style="background:#fff;padding:20px;margin:20px 0;border:1px solid #ccc;border-radius:5px;">
            <h2>🔧 Troubleshooting</h2>
            <form method="post">
                <?php wp_nonce_field('npf_flush_action', 'npf_flush_nonce'); ?>
                <input type="submit" name="npf_flush_rewrite" class="button" value="🔄 Fix 404 (Flush Permalinks)">
            </form>
        </div>
    </div>
    <?php
}

add_action('wp_ajax_npf_download_template', 'npf_download_template');
function npf_download_template() {
    if (!current_user_can('manage_options')) wp_die('Unauthorized');
    $format = isset($_GET['format']) ? sanitize_text_field($_GET['format']) : 'csv';
    if ($format === 'csv')  npf_download_csv_template();
    if ($format === 'json') npf_download_json_template();
    exit;
}

function npf_download_csv_template() {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=business-import-template.csv');
    $output = fopen('php://output', 'w');
    $headers = ['title','category','description','phone','email','website','address','google_maps','instagram','facebook','tiktok','priority'];
    fputcsv($output, $headers);
    fputcsv($output, ['AMARTA Nusa Penida','accommodation','Luxury resort','+62 812-3456-7890','info@amarta.com','https://amarta.com','Jl. Raya Nusa Penida','https://maps.google.com/?q=-8.7292,115.5444','https://instagram.com/amarta','https://facebook.com/amarta','https://tiktok.com/@amarta','10']);
    fclose($output);
}

function npf_download_json_template() {
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename=business-import-template.json');
    echo json_encode([[
        'title' => 'AMARTA Nusa Penida', 'category' => 'accommodation',
        'description' => 'Luxury resort', 'phone' => '+62 812-3456-7890',
        'email' => 'info@amarta.com', 'website' => 'https://amarta.com',
        'address' => 'Jl. Raya Nusa Penida',
        'google_maps' => 'https://maps.google.com/?q=-8.7292,115.5444',
        'instagram' => 'https://instagram.com/amarta',
        'facebook' => 'https://facebook.com/amarta',
        'tiktok' => 'https://tiktok.com/@amarta', 'priority' => 10,
    ]], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function npf_handle_import_preview() {
    if (!isset($_POST['npf_import_nonce']) || !wp_verify_nonce($_POST['npf_import_nonce'], 'npf_import_action')) wp_die('Security check failed');
    if (!current_user_can('manage_options')) wp_die('Unauthorized');

    if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
        echo '<div class="notice notice-error"><p>Please select a file.</p></div>'; return;
    }
    $file = $_FILES['import_file'];
    if ($file['size'] > 2 * 1024 * 1024) {
        echo '<div class="notice notice-error"><p>File exceeds 2MB.</p></div>'; return;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $data = $ext === 'csv' ? npf_parse_csv($file['tmp_name'])
          : ($ext === 'json' ? npf_parse_json($file['tmp_name']) : []);
    if (empty($data)) {
        echo '<div class="notice notice-error"><p>No valid data found.</p></div>'; return;
    }

    $import_mode      = isset($_POST['import_mode']) ? sanitize_text_field($_POST['import_mode']) : 'add';
    $default_category = isset($_POST['default_category']) ? intval($_POST['default_category']) : 0;
    $preview          = npf_validate_import_data($data, $import_mode);

    $stats = ['new' => 0, 'update' => 0, 'error' => 0];
    foreach ($preview as $item) $stats[$item['status']]++;
    ?>
    <div style="background:#fff;padding:20px;margin:20px 0;border:1px solid #ccc;border-radius:5px;">
        <h2>👀 Preview</h2>
        <p><strong><?php echo count($preview); ?></strong> rows. ✅ New: <?php echo $stats['new']; ?> · 🔄 Update: <?php echo $stats['update']; ?> · ❌ Error: <?php echo $stats['error']; ?></p>

        <div style="max-height:400px;overflow:auto;border:1px solid #eee;">
            <table class="widefat striped">
                <thead><tr><th>Status</th><th>Title</th><th>Category</th><th>Phone</th><th>Priority</th><th>Notes</th></tr></thead>
                <tbody>
                    <?php foreach ($preview as $item) :
                        $d = $item['data'];
                        $phone_disp = npf_phone_is_invalid($d['phone'] ?? '') ? '— (placeholder)' : ($d['phone'] ?? '');
                    ?>
                        <tr>
                            <td><?php echo esc_html(strtoupper($item['status'])); ?></td>
                            <td><?php echo esc_html($d['title'] ?? ''); ?></td>
                            <td><?php echo esc_html($d['category'] ?? ''); ?></td>
                            <td><?php echo esc_html($phone_disp); ?></td>
                            <td><?php echo esc_html($d['priority'] ?? ''); ?></td>
                            <td><?php echo esc_html($item['message']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <form method="post" style="margin-top:15px;">
            <?php wp_nonce_field('npf_import_confirm_action', 'npf_import_confirm_nonce'); ?>
            <input type="hidden" name="import_data" value="<?php echo esc_attr(base64_encode(wp_json_encode($preview))); ?>">
            <input type="hidden" name="import_mode" value="<?php echo esc_attr($import_mode); ?>">
            <input type="hidden" name="default_category" value="<?php echo esc_attr($default_category); ?>">
            <input type="submit" name="npf_confirm_import" class="button button-primary button-large" value="✅ Confirm &amp; Import <?php echo count($preview); ?> rows">
        </form>
    </div>
    <?php
}

function npf_parse_csv($path) {
    $rows = [];
    if (($h = fopen($path, 'r')) === false) return $rows;
    $headers = fgetcsv($h);
    if (!$headers) { fclose($h); return $rows; }
    $headers = array_map('strtolower', array_map('trim', $headers));
    while (($row = fgetcsv($h)) !== false) {
        if (count($row) === count($headers)) $rows[] = array_combine($headers, $row);
    }
    fclose($h);
    return $rows;
}

function npf_parse_json($path) {
    $content = file_get_contents($path);
    $data    = json_decode($content, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) return [];
    $out = [];
    foreach ($data as $item) {
        $norm = [];
        foreach ($item as $k => $v) $norm[strtolower(trim($k))] = $v;
        $out[] = $norm;
    }
    return $out;
}

function npf_validate_import_data($data, $import_mode) {
    $out = [];
    foreach ($data as $item) {
        $row = ['data' => $item, 'status' => 'new', 'message' => '', 'post_id' => 0];
        if (empty($item['title'])) {
            $row['status']  = 'error';
            $row['message'] = 'Missing required field: title';
            $out[] = $row; continue;
        }
        $existing = npf_get_business_by_title($item['title']);
        if ($existing) {
            $row['post_id'] = $existing->ID;
            if ($import_mode === 'add') {
                $row['status']  = 'error';
                $row['message'] = 'Already exists (skipped)';
            } else {
                $row['status']  = 'update';
                $row['message'] = 'Will update existing';
            }
        } else {
            $row['message'] = 'Will create new';
        }
        $out[] = $row;
    }
    return $out;
}

function npf_get_business_by_title($title) {
    $q = new WP_Query([
        'post_type'      => 'business',
        'title'          => $title,
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'no_found_rows'  => true,
    ]);
    return $q->have_posts() ? $q->posts[0] : null;
}

function npf_handle_import_confirm() {
    if (!isset($_POST['npf_import_confirm_nonce']) || !wp_verify_nonce($_POST['npf_import_confirm_nonce'], 'npf_import_confirm_action')) wp_die('Security check failed');
    if (!current_user_can('manage_options')) wp_die('Unauthorized');

    $encoded = isset($_POST['import_data']) ? wp_unslash($_POST['import_data']) : '';
    $rows    = json_decode(base64_decode($encoded), true);
    if (empty($rows)) { echo '<div class="notice notice-error"><p>No import data.</p></div>'; return; }

    $import_mode      = isset($_POST['import_mode']) ? sanitize_text_field($_POST['import_mode']) : 'add';
    $default_category = isset($_POST['default_category']) ? intval($_POST['default_category']) : 0;
    $results          = npf_process_import($rows, $import_mode, $default_category);
    ?>
    <div style="background:#fff;padding:20px;margin:20px 0;border:1px solid #ccc;border-radius:5px;">
        <h2>✅ Import Complete</h2>
        <p>Imported: <strong><?php echo (int) $results['success']; ?></strong> · Updated: <strong><?php echo (int) $results['updated']; ?></strong> · Failed: <strong><?php echo (int) $results['failed']; ?></strong></p>
        <p>
            <a class="button button-primary" href="<?php echo esc_url(admin_url('edit.php?post_type=business')); ?>">View businesses</a>
            <a class="button" href="<?php echo esc_url(admin_url('edit.php?post_type=business&page=npf-import')); ?>">Import more</a>
        </p>
    </div>
    <?php
}

function npf_process_import($rows, $import_mode, $default_category) {
    $results = ['success' => 0, 'updated' => 0, 'failed' => 0];
    foreach ($rows as $item) {
        if ($item['status'] === 'error' && $import_mode === 'add') { $results['failed']++; continue; }
        $data    = $item['data'];
        $post_id = (int) $item['post_id'];

        $post_data = [
            'post_title'   => sanitize_text_field($data['title']),
            'post_name'    => sanitize_title($data['title']),
            'post_type'    => 'business',
            'post_status'  => 'publish',
            'post_content' => isset($data['description']) ? sanitize_textarea_field($data['description']) : '',
        ];

        if ($post_id > 0 && in_array($import_mode, ['update', 'add_update'], true)) {
            $post_data['ID'] = $post_id;
            $r = wp_update_post($post_data, true);
            if (!is_wp_error($r)) { $results['updated']++; $results['success']++; }
            else                  { $results['failed']++; continue; }
        } else {
            $r = wp_insert_post($post_data, true);
            if (!is_wp_error($r)) { $post_id = $r; $results['success']++; }
            else                  { $results['failed']++; continue; }
        }

        $meta_fields = [
            'phone' => 'text', 'email' => 'email', 'website' => 'url',
            'address' => 'textarea', 'description' => 'textarea',
            'google_maps' => 'url', 'instagram' => 'url', 'facebook' => 'url', 'tiktok' => 'url',
            'priority' => 'number',
        ];
        foreach ($meta_fields as $field => $type) {
            if (!isset($data[$field]) || $data[$field] === '') continue;
            $value = $data[$field];
            switch ($type) {
                case 'url':      $value = esc_url_raw($value); break;
                case 'email':    $value = sanitize_email($value); break;
                case 'number':   $value = intval($value); break;
                case 'textarea': $value = sanitize_textarea_field($value); break;
                default:         $value = sanitize_text_field($value);
            }
            update_post_meta($post_id, '_business_' . $field, $value);
        }

        if (!empty($data['category'])) {
            $category = sanitize_text_field($data['category']);
            $term     = get_term_by('slug', $category, 'business_category')
                     ?: get_term_by('name', $category, 'business_category');
            if ($term)                              wp_set_object_terms($post_id, $term->term_id, 'business_category');
            elseif ($default_category > 0)          wp_set_object_terms($post_id, $default_category, 'business_category');
        } elseif ($default_category > 0) {
            wp_set_object_terms($post_id, $default_category, 'business_category');
        }
    }
    flush_rewrite_rules();
    return $results;
}
