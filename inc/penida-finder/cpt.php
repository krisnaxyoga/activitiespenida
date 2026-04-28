<?php
/**
 * Penida Finder — Business CPT + Category taxonomy.
 * Mirrors the standalone plugin so existing data stays accessible.
 */

if (!defined('ABSPATH')) exit;

add_action('init', 'npf_register_business_post_type');
function npf_register_business_post_type() {
    $labels = [
        'name'               => 'Business Listings',
        'singular_name'      => 'Business Listing',
        'menu_name'          => 'Nusa Penida Business',
        'name_admin_bar'     => 'Business Listing',
        'add_new'            => 'Add New Business',
        'add_new_item'       => 'Add New Business',
        'new_item'           => 'New Business',
        'edit_item'          => 'Edit Business',
        'view_item'          => 'View Business',
        'all_items'          => 'All Businesses',
        'search_items'       => 'Search Businesses',
        'parent_item_colon'  => 'Parent Businesses:',
        'not_found'          => 'No businesses found.',
        'not_found_in_trash' => 'No businesses found in Trash.',
    ];

    register_post_type('business', [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'business'],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-store',
        'supports'           => ['title', 'thumbnail'],
        'show_in_rest'       => true,
    ]);
}

add_action('init', 'npf_register_business_category_taxonomy');
function npf_register_business_category_taxonomy() {
    $labels = [
        'name'              => 'Business Categories',
        'singular_name'     => 'Business Category',
        'search_items'      => 'Search Categories',
        'all_items'         => 'All Categories',
        'parent_item'       => 'Parent Category',
        'parent_item_colon' => 'Parent Category:',
        'edit_item'         => 'Edit Category',
        'update_item'       => 'Update Category',
        'add_new_item'      => 'Add New Category',
        'new_item_name'     => 'New Category Name',
        'menu_name'         => 'Categories',
    ];

    register_taxonomy('business_category', ['business'], [
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'business-category'],
        'show_in_rest'      => true,
    ]);
}

add_action('init', 'npf_business_rewrite_rules', 11);
function npf_business_rewrite_rules() {
    add_rewrite_rule(
        '^business/([^/]+)/?$',
        'index.php?post_type=business&name=$matches[1]',
        'top'
    );
}

add_action('admin_init', 'npf_maybe_seed_categories_and_flush');
function npf_maybe_seed_categories_and_flush() {
    if (get_option('npf_seeded') === '1') return;

    $defaults = [
        'Accommodation', 'Restaurant & Cafe', 'Tour Guide', 'Transportation',
        'Water Sports', 'Souvenir Shop', 'Spa & Massage', 'Adventure Tours',
    ];
    foreach ($defaults as $category) {
        if (!term_exists($category, 'business_category')) {
            wp_insert_term($category, 'business_category');
        }
    }
    flush_rewrite_rules(false);
    update_option('npf_seeded', '1');
}
