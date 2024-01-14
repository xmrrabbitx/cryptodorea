<?php

defined( 'ABSPATH' ) || exit;

include_once(WP_PLUGIN_DIR . '/dorea/config/conf.php');


/**
 * add menu options to admin panels
 */
add_action('admin_menu', 'dorea_add_menu_page');

function dorea_add_menu_page() {

    $logo_path = plugin_dir_path(__FILE__) . 'icons/doreaLogo.svg';

    if (file_exists($logo_path)) {
        $logo_content = file_get_contents($logo_path);
        $base64_encoded = base64_encode($logo_content);

        /**
         * Dorea Cash Back Main Menu
         */
        add_menu_page(
            'Dorea Cash Back',   // Page title
            'Dorea Cash Back',        // Menu title
            'manage_options',     // Capability required to access
            'crypto-dorea-cashback',   // Menu slug (unique identifier)
            'dorea_main_page_content', // Callback function to display page content
            'data:image/svg+xml;base64,' . $base64_encoded, // Icon URL or dashicon class
            20 // Menu position (you can adjust this)
        );

        /**
         * Setting Menu
         */
        add_submenu_page(
            'crypto-dorea-cashback',
            'Setting Page',
            'settings',
            'manage_options',
            'settings',
            'dorea_main_setting_content'
        );


        /**
         * Campaign Menu
         */
        add_submenu_page(
            'crypto-dorea-cashback',
            'Campaign Page',
            'cashback campaigns',
            'manage_options',
            'campaigns',
            'dorea_cashback_campaign_content'
        );
    
    }
    
}

/**
 *  main page content
 */ 
function dorea_main_page_content(){

    $cashback = new cashback();
    $cashbackList = $cashback->list();

    print("create cash back program </br>");
    foreach ($cashbackList as &$campaignList) {
        print($campaignList . '</br>');
        
    }
    
    
}

/**
 * setting page _ set up init config
 */
include('setting.php');


/**
 * Crypto Cashback Campaign
 */
include('campaign.php');


