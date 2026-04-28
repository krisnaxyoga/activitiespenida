<?php
/**
 * Penida Finder — priority sidebar meta-box, save, sort and the
 * ⭐ Featured badge on the title (drop-in replacement for the plugin's module).
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', 'npf_add_priority_metabox');
function npf_add_priority_metabox() {
    add_meta_box(
        'npf_business_priority',
        'Priority &amp; Featured',
        'npf_priority_callback',
        'business',
        'side',
        'high'
    );
}

function npf_priority_callback($post) {
    wp_nonce_field('npf_save_priority', 'npf_priority_nonce');
    $priority = get_post_meta($post->ID, '_business_priority', true);
    ?>
    <div style="padding:10px 0;">
        <label style="display:block;margin-bottom:10px;font-weight:bold;" for="business_priority">Priority order</label>
        <input type="number" id="business_priority" name="business_priority" value="<?php echo esc_attr($priority); ?>" min="0" style="width:100%;padding:8px;">
        <p style="margin:10px 0 0 0;font-size:12px;color:#666;line-height:1.5;">
            <strong>Higher number = appears at the top.</strong><br>
            Priority &gt; 0 shows a ⭐ Featured badge on the frontend.
        </p>
        <?php if ($priority > 0) : ?>
            <div style="margin-top:15px;padding:10px;background:#d4edda;border:1px solid #c3e6cb;border-radius:4px;">
                <strong style="color:#155724;">✅ This business is featured.</strong>
            </div>
        <?php else : ?>
            <div style="margin-top:15px;padding:10px;background:#f8f9fa;border:1px solid #dee2e6;border-radius:4px;">
                <span style="color:#6c757d;">Set priority &gt; 0 to feature this business.</span>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

add_action('save_post_business', 'npf_save_priority');
function npf_save_priority($post_id) {
    if (!isset($_POST['npf_priority_nonce']) || !wp_verify_nonce($_POST['npf_priority_nonce'], 'npf_save_priority')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $priority = isset($_POST['business_priority']) ? intval($_POST['business_priority']) : 0;
    update_post_meta($post_id, '_business_priority', $priority);
}

add_action('pre_get_posts', 'npf_sort_business_by_priority');
function npf_sort_business_by_priority($query) {
    if (
        !is_admin()
        && $query->is_main_query()
        && ($query->get('post_type') === 'business' || is_post_type_archive('business'))
    ) {
        $query->set('meta_key', '_business_priority');
        $query->set('orderby', [
            'meta_value_num' => 'DESC',
            'title'          => 'ASC',
        ]);
    }

    if (is_admin() && $query->is_main_query()) {
        if ($query->get('orderby') === 'priority') {
            $query->set('meta_key', '_business_priority');
            $query->set('orderby', 'meta_value_num');
        }
    }
}

add_filter('the_title', 'npf_star_title', 10, 2);
function npf_star_title($title, $id) {
    if (
        get_post_type($id) === 'business'
        && !is_admin()
        && !in_array($GLOBALS['pagenow'] ?? '', ['wp-login.php', 'admin-ajax.php'], true)
    ) {
        $priority = (int) get_post_meta($id, '_business_priority', true);
        if ($priority > 0) {
            $title .= ' <span class="featured-badge">⭐ Featured</span>';
        }
    }
    return $title;
}
