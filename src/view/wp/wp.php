<?php

/**
 * wp on each user request
 */

use Cryptodorea\DoreaCashback\controllers\checkoutController;
use function Cryptodorea\DoreaCashback\view\modals\userStatusCampaign\userStatusCampaign;

add_action('wp','wpRequest');
/**
 * @throws Exception
 */
function wpRequest()
{

    // check on Authentication
    if(is_user_logged_in()) {

        // core js style
        wp_enqueue_script('DOREA_CORE_STYLE', plugins_url('/cryptodorea/js/style.min.js'));

        // autoremove deleted campaigns
        $checkout = new checkoutController();
        $checkout->autoRemove();


        //userStatusCampaign();


        // insert Dorea option into user menu
        function dorea_cashback_menu($items)
        {
            // Remove the logout menu item.
            $logout = $items['customer-logout'];
            unset($items['customer-logout']);

            // Insert your dorea cashback menu item
            $items['dorea_cashbback_menu'] = __('Dorea Cashback', 'woocommerce');

            // Insert back the logout item.
            $items['customer-logout'] = $logout;

            return $items;
        }
        add_filter('woocommerce_account_menu_items', 'dorea_cashback_menu');

        add_filter('script_loader_tag', 'add_type_cashbackmenu', 10, 3);
        function add_type_cashbackmenu($tag, $handle, $src)
        {
            // if not your script, do nothing and return original $tag
            if ('DOREA_CASHBACKMENU_SCRIPT' !== $handle) {
                return $tag;
            }
            // change the script tag by adding type="module" and return it.
            $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
            return $tag;
        }

    }



}
