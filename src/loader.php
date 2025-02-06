<?php

/**
 * loader class for dorea file
 */

// check security
defined( 'ABSPATH' ) || exit;

/**
 * load necessary admin files
 */
include_once DOREA_PLUGIN_DIR . '/src/view/admin/doreaMain.php';
include_once DOREA_PLUGIN_DIR . '/src/view/checkout/doreaCheckout.php';
include_once DOREA_PLUGIN_DIR . '/src/view/doreaMenu/doreaMenu.php';
include_once DOREA_PLUGIN_DIR . '/src/view/modals/userStatusCampaign.php';

// admin panel full loads
add_action('admin_menu','dorea_init');
function dorea_init():void
{
    /**
     * load necessary libraries files
     * tailwind css
     */
    // load campaign credit scripts
    wp_enqueue_script('DOREA_CAMPAIGNCREDIT_SCRIPT', 'https://cdn.tailwindcss.com', array('jquery', 'jquery-ui-core'),
        array(),
        1,
        true
    );

}