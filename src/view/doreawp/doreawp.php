<?php

/**
 * wp on each user request
 */
use Cryptodorea\DoreaCashback\controllers\checkoutController;
use function Cryptodorea\DoreaCashback\view\modals\userStatusCampaign\userStatusCampaign;


add_action('woocommerce_account_content','myAccount', 10);
function myAccount()
{
    userStatusCampaign();
    // add module type to script
    add_filter('script_loader_tag', 'add_type_userStatusCampaign', 10, 3);
    function add_type_userStatusCampaign($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_USERSTATUSCAMPAIGN_SCRIPT' !== $handle) {
            return $tag;
        }

        $position = strpos($tag, 'src="') - 1;
        // change the script tag by adding type="module" and return it.
        $outTag = substr($tag, 0, $position) . ' type="module" ' . substr($tag, $position);

        return $outTag;
    }
}

add_action('wp','doreawpRequest', 10);
/**
 * @throws Exception
 */
function doreawpRequest()
{
    // check on Authentication
    if(is_user_logged_in()) {

        // core js style
        wp_enqueue_script('DOREA_CORE_STYLE', plugins_url('/cryptodorea/js/style.min.js'),
            array(),
            1,
            true
        );

        // autoremove deleted campaigns
        $checkout = new checkoutController();
        $checkout->autoRemove();

        /**
         * add Dorea menu into my account menu
         */
        function dorea_cashback_menu($items)
        {
            // Remove the logout menu item.
            $logout = $items['customer-logout'];
            unset($items['customer-logout']);

            // Insert your dorea cashback menu item
            $items['dorea_cashbback_menu'] = __('Dorea Cashback', 'cryptodorea');

            // Insert back the logout item.
            $items['customer-logout'] = $logout;

            return $items;
        }
        add_filter('woocommerce_account_menu_items', 'dorea_cashback_menu');

    }
}
