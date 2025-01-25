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
    // remove admin footer
    //add_filter( 'admin_footer_text', '__return_empty_string', 11 );
    //add_filter( 'update_footer', '__return_empty_string', 11 );

    // core js style
    wp_enqueue_script('DOREA_CORE_STYLE', plugins_url('/cryptodorea/js/style.min.js'));

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_deployfailbreak', 10, 3);
    function add_type_deployfailbreak($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_DEPLOYFAILBREAK_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_fundfailbreak', 10, 3);
    function add_type_fundfailbreak($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_FUNDFAILBREAK_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_payfailbreak', 10, 3);
    function add_type_payfailbreak($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_PAYFAILBREAK_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_campaigncredit', 10, 3);
    function add_type_campaigncredit($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_CAMPAIGNCREDIT_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_checkout', 10, 3);
    function add_type_checkout($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_CHECKOUT_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_checkout_legacy', 10, 3);
    function add_type_checkout_legacy($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_CHECKOUTLEGACY_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_checkoutbeforeproccessed', 10, 3);
    function add_type_checkoutbeforeproccessed($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_CHECKOUT_BEFORE_PROCESSED_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_ordered', 10, 3);
    function add_type_ordered($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_ORDERED_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to script
    add_filter('script_loader_tag', 'add_type_userStatusCampaign', 10, 3);
    function add_type_userStatusCampaign($tag, $handle, $src)
    {

        // if not your script, do nothing and return original $tag
        if ('DOREA_USERSTATUSCAMPAIGN_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    // add module type to script
    add_filter('script_loader_tag', 'add_type_pay', 10, 3);
    function add_type_pay($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_PAY_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    add_filter('script_loader_tag', 'add_type_fund', 10, 3);
    function add_type_fund($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_FUND_SCRIPT' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

}