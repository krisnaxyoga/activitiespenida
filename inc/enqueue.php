<?php
add_action('wp_enqueue_scripts', 'theme_enqueue');
function theme_enqueue() {
    $ver = wp_get_theme()->get('Version');
    wp_enqueue_style('theme-main-style', get_template_directory_uri() . '/dist/css/main.css', [], $ver);
    wp_enqueue_style('theme-custom-style', get_template_directory_uri() . '/dist/css/customs.css', [], $ver);
    wp_enqueue_style('theme-bisnis-style', get_template_directory_uri() . '/dist/css/bisnis-directory.css', [], $ver);
    wp_enqueue_script('theme-script', get_template_directory_uri() . '/dist/js/main.js', [], $ver, true);
    wp_enqueue_script('theme-bisnis-script', get_template_directory_uri() . '/dist/js/bisnis-directory.js', [], $ver, true);
}