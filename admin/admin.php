<?php

defined( 'ABSPATH' ) || exit;

/**
 * add a main page to menu in wordpress
 */
add_action('admin_menu', 'dorea_add_menu_page');

function dorea_add_menu_page() {

   

    $svg_path = plugin_dir_path(__FILE__) . 'icons/doreaLogo.svg';

    if (file_exists($svg_path)) {
        $svg_content = file_get_contents($svg_path);
        $base64_encoded = base64_encode($svg_content);

        add_menu_page(
            'Dorea Cash Back',   // Page title
            'Dorea Cash Back',        // Menu title
            'manage_options',     // Capability required to access
            'crypto-dorea-cashback',   // Menu slug (unique identifier)
            'dorea_main_page_content', // Callback function to display page content
            'data:image/svg+xml;base64,' . $base64_encoded, // Icon URL or dashicon class
            20 // Menu position (you can adjust this)
        );
    
    }
    
}

function dorea_main_page_content(){

    print("create cash back program");
    print("</br>");
    print("
        <form type='POST' action=''>
            <label>expire</label>
            <input type='text'>
            <button type='submit'>set expire date</button>
        </form>
    ");
}