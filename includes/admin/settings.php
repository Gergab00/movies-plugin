<?php

function movies_plugin_admin_notice() {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><strong>Movies Plugin:</strong> You need to have Bootstrap v5.2 installed in your theme for the plugin to work properly.</p>
    </div>
    <?php
}
add_action( 'admin_notices', 'movies_plugin_admin_notice' );

function movies_plugin_dismiss_notice() {
    wp_enqueue_script( 'movies-plugin-dismiss-notice', plugin_dir_url( __FILE__ ) . 'js/dismiss-notice.js', array( 'jquery' ), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'movies_plugin_dismiss_notice' );

function movies_plugin_dismiss_notice_ajax_handler() {
    update_option( 'movies_plugin_dismiss_notice', true );
    wp_die();
}
add_action( 'wp_ajax_movies_plugin_dismiss_notice', 'movies_plugin_dismiss_notice_ajax_handler' );

function assign_page_movies_template() {

    $movies_plugin_options = get_option( 'movies_plugin_options' );

    $page_id = ($movies_plugin_options['pages'] != '') ? $movies_plugin_options['pages'] : '';

    if ( get_the_ID() == $page_id ) {
    
        include( MOVIES_PLUGIN_DIR . '/templates/page-movies.php' );
        exit;
    }
}
add_action( 'template_redirect', 'assign_page_movies_template' );

function assign_page_actor_template() {

    $movies_plugin_options = get_option( 'movies_plugin_options' );

    $page_id = ($movies_plugin_options['pages'] != '') ? $movies_plugin_options['pages'] : '';

    if ( get_the_ID() == $page_id ) {
    
        include( MOVIES_PLUGIN_DIR . '/templates/page-actor.php' );
        exit;
    }
}
add_action( 'template_redirect', 'assign_page_actor_template' );

function add_styles() {
    $plugin_data = get_file_data( __FILE__, array( 'Version' => 'Version' ) );
    wp_enqueue_style( 'movies-plugin-styles', MOVIES_PLUGIN_URL.'assets/css/style.css', array(), $plugin_data['Version'] );
}
add_action( 'wp_enqueue_scripts', 'add_styles' );
