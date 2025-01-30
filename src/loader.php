<?php

/**
 * loader class for dorea file
 */

// check security
defined( 'ABSPATH' ) || exit;
if( ! defined('WCSF_PLUGIN_FILE') ) {
    define( 'WCSF_PLUGIN_FILE', __FILE__ );
}

if( ! defined('WCSF_PLUGIN_DIR') ) {
    define( 'WCSF_PLUGIN_DIR', __DIR__ );
}

/**
 * load necessary admin files
 */
include_once WP_PLUGIN_DIR . '/cryptodorea/src/view/admin/admin.php';
include_once WP_PLUGIN_DIR . '/cryptodorea/src/view/checkout/checkout.php';
include_once WP_PLUGIN_DIR . '/cryptodorea/src/view/doreawp/doreawp.php';
include_once WP_PLUGIN_DIR . '/cryptodorea/src/view/modals/userStatusCampaign.php';

// admin panel full loads
add_action('admin_menu','admin_init');
function admin_init():void
{
    // core js style
    wp_enqueue_script('DOREA_CORE_STYLE', plugins_url('/cryptodorea/js/style.min.js'),
        array(),
        1,
        true
    );
}