<?php
// contoh AJAX handler kalau perlu
add_action('wp_ajax_nopriv_load_more', 'ajax_load_more');
add_action('wp_ajax_load_more', 'ajax_load_more');
function ajax_load_more() {
    // logic here
    wp_die();
}