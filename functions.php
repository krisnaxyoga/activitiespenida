<?php
foreach (glob(get_template_directory() . '/inc/*.php') as $file) {
    require_once $file;
}
add_filter( 'rest_pre_dispatch', function( $result, $server, $request ) {

    $blocked_routes = array(
        '/wp/v2/users',
        '/wp/v2/settings',
        '/wp/v2/media',
    );

    foreach ( $blocked_routes as $route ) {
        if ( strpos( $request->get_route(), $route ) === 0 ) {

            if ( ! is_user_logged_in() ) {
                return new WP_Error(
                    'rest_forbidden',
                    'REST API endpoint is restricted.',
                    array( 'status' => 403 )
                );
            }

        }
    }

    return $result;

}, 10, 3 );
