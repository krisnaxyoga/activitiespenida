<?php
/**
 * Penida Finder — admin list columns, sort, filters, quick stats.
 */

if (!defined('ABSPATH')) exit;

add_filter('manage_business_posts_columns', 'npf_set_custom_columns');
function npf_set_custom_columns($columns) {
    $new = [];
    $new['cb']                = $columns['cb'] ?? '';
    $new['title']             = $columns['title'] ?? 'Title';
    $new['business_category'] = 'Category';
    $new['priority']          = 'Priority';
    $new['phone']             = 'Phone';
    $new['email']             = 'Email';
    $new['social']            = 'Social';
    $new['date']              = $columns['date'] ?? 'Date';
    return $new;
}

add_action('manage_business_posts_custom_column', 'npf_custom_column_content', 10, 2);
function npf_custom_column_content($column, $post_id) {
    switch ($column) {
        case 'business_category':
            $terms = get_the_terms($post_id, 'business_category');
            if ($terms && !is_wp_error($terms)) {
                $names = [];
                foreach ($terms as $term) {
                    $names[] = '<span style="background:#667eea;color:#fff;padding:2px 8px;border-radius:10px;font-size:11px;">' . esc_html($term->name) . '</span>';
                }
                echo implode(' ', $names);
            } else {
                echo '<span style="color:#999;">—</span>';
            }
            break;

        case 'priority':
            $priority = (int) get_post_meta($post_id, '_business_priority', true);
            if ($priority > 0) {
                echo '<strong style="color:#d4af37;">⭐ ' . esc_html($priority) . '</strong>';
            } else {
                echo '<span style="color:#999;">0</span>';
            }
            break;

        case 'phone':
            $phone = get_post_meta($post_id, '_business_phone', true);
            if (npf_phone_is_invalid($phone)) {
                echo '<span style="color:#b45309;" title="Empty or placeholder — frontend shows a backlink">—</span>';
            } else {
                echo esc_html($phone);
            }
            break;

        case 'email':
            $email = get_post_meta($post_id, '_business_email', true);
            echo $email ? esc_html($email) : '<span style="color:#999;">—</span>';
            break;

        case 'social':
            $links       = [];
            $google_maps = get_post_meta($post_id, '_business_google_maps', true);
            $instagram   = get_post_meta($post_id, '_business_instagram', true);
            $facebook    = get_post_meta($post_id, '_business_facebook', true);
            $tiktok      = get_post_meta($post_id, '_business_tiktok', true);

            if ($google_maps) $links[] = '<a href="' . esc_url($google_maps) . '" target="_blank" title="Google Maps">📍</a>';
            if ($instagram)   $links[] = '<a href="' . esc_url($instagram)   . '" target="_blank" title="Instagram">📷</a>';
            if ($facebook)    $links[] = '<a href="' . esc_url($facebook)    . '" target="_blank" title="Facebook">👥</a>';
            if ($tiktok)      $links[] = '<a href="' . esc_url($tiktok)      . '" target="_blank" title="TikTok">🎵</a>';

            echo $links ? implode(' ', $links) : '<span style="color:#999;">—</span>';
            break;
    }
}

add_filter('manage_edit-business_sortable_columns', 'npf_sortable_columns');
function npf_sortable_columns($columns) {
    $columns['priority']          = 'priority';
    $columns['business_category'] = 'business_category';
    return $columns;
}

add_action('restrict_manage_posts', 'npf_add_admin_filters');
function npf_add_admin_filters() {
    global $typenow;
    if ($typenow !== 'business') return;

    $selected_category = isset($_GET['business_category']) ? sanitize_text_field($_GET['business_category']) : '';
    wp_dropdown_categories([
        'show_option_all' => 'All Categories',
        'taxonomy'        => 'business_category',
        'name'            => 'business_category',
        'value_field'     => 'slug',
        'selected'        => $selected_category,
        'hide_empty'      => false,
        'hierarchical'    => true,
    ]);

    $priority_filter = isset($_GET['priority_filter']) ? sanitize_text_field($_GET['priority_filter']) : '';
    ?>
    <select name="priority_filter">
        <option value="">All Priority Levels</option>
        <option value="featured" <?php selected($priority_filter, 'featured'); ?>>⭐ Featured Only (Priority &gt; 0)</option>
        <option value="regular"  <?php selected($priority_filter, 'regular'); ?>>Regular (Priority = 0)</option>
        <option value="high"     <?php selected($priority_filter, 'high'); ?>>High Priority (≥ 10)</option>
    </select>
    <?php
    $total          = (int) (wp_count_posts('business')->publish ?? 0);
    $featured_count = npf_count_featured_businesses();
    ?>
    <span style="margin-left:10px;padding:5px 10px;background:#f0f0f1;border-radius:3px;font-size:12px;">
        Total: <strong><?php echo esc_html($total); ?></strong> |
        Featured: <strong style="color:#d4af37;"><?php echo esc_html($featured_count); ?></strong>
    </span>
    <?php
}

function npf_count_featured_businesses() {
    global $wpdb;
    $count = $wpdb->get_var("
        SELECT COUNT(*)
        FROM {$wpdb->postmeta}
        WHERE meta_key = '_business_priority'
        AND CAST(meta_value AS UNSIGNED) > 0
    ");
    return $count ? (int) $count : 0;
}

add_filter('parse_query', 'npf_apply_admin_filters');
function npf_apply_admin_filters($query) {
    global $pagenow, $typenow;
    if ($pagenow !== 'edit.php' || $typenow !== 'business' || !is_admin()) return;
    if (empty($_GET['priority_filter'])) return;

    $filter = sanitize_text_field($_GET['priority_filter']);
    if ($filter === 'featured') {
        $query->query_vars['meta_query'] = [[
            'key' => '_business_priority', 'value' => 0, 'compare' => '>', 'type' => 'NUMERIC',
        ]];
    } elseif ($filter === 'regular') {
        $query->query_vars['meta_query'] = [
            'relation' => 'OR',
            ['key' => '_business_priority', 'value' => 0, 'compare' => '=', 'type' => 'NUMERIC'],
            ['key' => '_business_priority', 'compare' => 'NOT EXISTS'],
        ];
    } elseif ($filter === 'high') {
        $query->query_vars['meta_query'] = [[
            'key' => '_business_priority', 'value' => 10, 'compare' => '>=', 'type' => 'NUMERIC',
        ]];
    }
}
