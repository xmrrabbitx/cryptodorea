<?php

defined( 'ABSPATH' ) || exit;

/**
 * add a main page to menu in wordpress
 */
add_action('admin_menu', 'dorea_add_menu_page');

function dorea_add_menu_page() {

    add_menu_page(
        'Dorea Cash Back',   // Page title
        'Dorea Cash Back',        // Menu title
        'manage_options',     // Capability required to access
        'your-plugin-slug',   // Menu slug (unique identifier)
        'dorea_main_page_content', // Callback function to display page content
        'dashicons-admin-plugins',  // Icon URL or dashicon class
        20 // Menu position (you can adjust this)
    );

}

function dorea_main_page_content(){

    print("create cash back program");
    print("</br>");
    print("set public key");
    print("</br>");
    print("set private key ");
}