<?php
/**
 * Penida Finder — settings page.
 * Adds an admin menu with a Tailwind-styled toggle. Activating the toggle
 * stores the option AND auto-deactivates the standalone plugin (data preserved).
 */

if (!defined('ABSPATH')) exit;

add_action('admin_menu', 'npf_register_settings_menu');
function npf_register_settings_menu() {
    add_menu_page(
        'Penida Finder',
        'Penida Finder',
        'manage_options',
        'penida-finder',
        'npf_render_settings_page',
        'dashicons-location',
        21
    );
}

add_action('admin_post_npf_save_settings', 'npf_handle_save_settings');
function npf_handle_save_settings() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    check_admin_referer('npf_save_settings', 'npf_settings_nonce');

    $new_state = isset($_POST['npf_active']) && $_POST['npf_active'] === '1' ? '1' : '0';
    $previous  = get_option(NPF_OPTION_KEY, '0');
    update_option(NPF_OPTION_KEY, $new_state);

    $deactivated = [];
    if ($new_state === '1') {
        if (!function_exists('deactivate_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        foreach (npf_find_directory_plugin_files() as $plugin_file) {
            if (is_plugin_active($plugin_file)) {
                deactivate_plugins($plugin_file, true);
                $deactivated[] = $plugin_file;
            }
        }
    }

    $args = ['page' => 'penida-finder', 'npf_saved' => '1'];
    if ($new_state !== $previous) $args['npf_changed'] = '1';
    if (!empty($deactivated)) $args['npf_plugin_off'] = count($deactivated);

    wp_safe_redirect(add_query_arg($args, admin_url('admin.php')));
    exit;
}

add_action('admin_enqueue_scripts', 'npf_enqueue_settings_assets');
function npf_enqueue_settings_assets($hook) {
    if ($hook !== 'toplevel_page_penida-finder') return;
    wp_enqueue_script(
        'npf-tailwind-cdn',
        'https://cdn.tailwindcss.com',
        [],
        NPF_VERSION,
        false
    );
}

function npf_render_settings_page() {
    if (!current_user_can('manage_options')) return;

    $active            = npf_is_active();
    $plugin_files      = npf_find_directory_plugin_files();
    $plugin_installed  = !empty($plugin_files);
    $plugin_active     = false;
    if ($plugin_installed) {
        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        foreach ($plugin_files as $file) {
            if (is_plugin_active($file)) { $plugin_active = true; break; }
        }
    }

    $business_count = wp_count_posts('business');
    $published      = isset($business_count->publish) ? (int) $business_count->publish : 0;
    $featured       = function_exists('npf_count_featured_businesses') ? npf_count_featured_businesses() : 0;
    ?>
    <div class="wrap">
        <div id="npf-app" class="max-w-5xl mx-auto py-6 font-sans antialiased text-slate-800">

            <?php if (isset($_GET['npf_saved'])) : ?>
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                    <span>Settings saved.<?php if (!empty($_GET['npf_plugin_off'])) : ?> The standalone plugin was auto-deactivated; saved data is preserved.<?php endif; ?></span>
                </div>
            <?php endif; ?>

            <header class="mb-6">
                <h1 class="text-2xl font-semibold tracking-tight">Penida Finder</h1>
                <p class="text-sm text-slate-500 mt-1">Native theme integration of the Nusa Penida Business Directory.</p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <section class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="space-y-6">
                        <input type="hidden" name="action" value="npf_save_settings">
                        <?php wp_nonce_field('npf_save_settings', 'npf_settings_nonce'); ?>

                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Finder Mode</h2>
                                <p class="text-sm text-slate-500 mt-1 max-w-prose">
                                    When active, this theme registers the <code>business</code> post type, meta-boxes,
                                    importer, priority module, and admin filters natively — and automatically deactivates
                                    the standalone plugin so they never collide. Existing businesses, categories, and
                                    meta data remain in the database either way.
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer select-none shrink-0 mt-1">
                                <input type="checkbox" name="npf_active" value="1" class="sr-only peer" <?php checked($active); ?>>
                                <span class="w-14 h-8 bg-slate-200 rounded-full peer peer-checked:bg-emerald-500 transition-colors"></span>
                                <span class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full shadow transition-transform peer-checked:translate-x-6"></span>
                            </label>
                        </div>

                        <div class="rounded-lg bg-slate-50 border border-slate-200 p-4 text-sm space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-600">Theme integration</span>
                                <?php if ($active) : ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-medium">Active</span>
                                <?php else : ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-200 text-slate-600 text-xs font-medium">Inactive</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-600">Standalone plugin</span>
                                <?php if (!$plugin_installed) : ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-200 text-slate-600 text-xs font-medium">Not installed</span>
                                <?php elseif ($plugin_active) : ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-medium">Installed &amp; active</span>
                                <?php else : ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-200 text-slate-600 text-xs font-medium">Installed (inactive)</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($active && $plugin_active) : ?>
                            <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                                Both are currently active. Saving will deactivate the plugin to avoid duplicate registrations. Your data stays intact.
                            </div>
                        <?php endif; ?>

                        <div class="pt-2 border-t border-slate-200 flex items-center gap-3">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium px-4 py-2 transition-colors">
                                Save changes
                            </button>
                            <span class="text-xs text-slate-500">Toggling off will not delete data — it only disables the theme integration.</span>
                        </div>
                    </form>
                </section>

                <aside class="space-y-6">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                        <h3 class="text-sm font-semibold text-slate-900 mb-3">At a glance</h3>
                        <dl class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-500">Total businesses</dt>
                                <dd class="font-semibold text-slate-900"><?php echo esc_html($published); ?></dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-500">Featured (priority &gt; 0)</dt>
                                <dd class="font-semibold text-amber-600">⭐ <?php echo esc_html($featured); ?></dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                        <h3 class="text-sm font-semibold text-slate-900 mb-2">Phone normalization</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Empty numbers and the placeholder <code class="px-1 py-0.5 rounded bg-slate-100">6281337567256</code> are
                            rendered as a "<span class="font-semibold">-</span>" anchor that backlinks to
                            <a href="<?php echo esc_url(npf_phone_fallback_url()); ?>" target="_blank" rel="noopener" class="text-blue-600 hover:underline">snorkelingpenida.com</a>
                            instead of producing broken <code>tel:</code> / <code>wa.me</code> links.
                        </p>
                    </div>

                    <?php if ($active) : ?>
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                            <h3 class="text-sm font-semibold text-slate-900 mb-3">Quick links</h3>
                            <ul class="space-y-2 text-sm">
                                <li><a class="text-blue-600 hover:underline" href="<?php echo esc_url(admin_url('edit.php?post_type=business')); ?>">All businesses →</a></li>
                                <li><a class="text-blue-600 hover:underline" href="<?php echo esc_url(admin_url('post-new.php?post_type=business')); ?>">Add new business →</a></li>
                                <li><a class="text-blue-600 hover:underline" href="<?php echo esc_url(admin_url('edit.php?post_type=business&page=npf-import')); ?>">Import businesses →</a></li>
                                <li><a class="text-blue-600 hover:underline" href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy=business_category&post_type=business')); ?>">Categories →</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </aside>

            </div>
        </div>
    </div>
    <?php
}
