<?php

/**
 * loader class for dorea file
 */

// check security
defined( 'ABSPATH' ) || exit;

/**
 * load necessary admin files
 */
include_once DOREA_PLUGIN_DIR . '/src/view/admin/dorea.php';
include_once DOREA_PLUGIN_DIR . '/src/view/checkout/doreaCheckout.php';
include_once DOREA_PLUGIN_DIR . '/src/view/doreaMenu/doreaMenu.php';
include_once DOREA_PLUGIN_DIR . '/src/view/modals/userStatusCampaign.php';

// admin panel full loads
add_action('admin_menu','dorea_init');
function dorea_init():void
{
    // core js style
    wp_enqueue_script('DOREA_CORE_STYLE', DOREA_PLUGIN_URL . ('/js/doreaStyle.min.js'),
        array(),
        1,
        true
    );
}