<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Cryptodorea\DoreaCashback\controllers\cashbackController;
use Cryptodorea\DoreaCashback\controllers\checkoutController;

/**
 * error handling of checkout fields
 */
add_action( 'woocommerce_checkout_process', 'dorea_checkout_field_process',9999 );
function dorea_checkout_field_process() {

    if(isset($_POST['dorea_wpnonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_POST['dorea_wpnonce']));
        if (wp_verify_nonce($nonce, 'dorea_checkout_nonce')) {

            // get cashback list of admin
            $cashback = new cashbackController();
            $cashbackList = $cashback->list();

            // get campaign list of user
            $checkoutController = new checkoutController;
            $diffCampaignsList = $checkoutController->check($cashbackList);

            if (!empty($diffCampaignsList)) {

                // show campaigns in checkout page
                if (!empty($cashbackList)) {

                    $checkoboxes = [];
                    foreach ($diffCampaignsList as $campaign) {

                        $campaignInfo = get_transient('dorea_' . $campaign);

                        // check if any campaign funded or not!
                        if (get_option('dorea_' . $campaign . '_contract_address')) {
                            // check if campaign started or not
                            if ($checkoutController->expire($campaign)) {
                                if (isset($_POST[$campaignInfo['campaignName']])){
                                    if (sanitize_text_field(wp_unslash($_POST[$campaignInfo['campaignName']]))) {
                                        $checkoboxes[] = true;
                                    }
                                }
                            }
                        }
                    }

                    if (in_array(true, $checkoboxes)) {
                        if (isset($_POST['dorea_wallet_address'])) {
                            if (empty(sanitize_text_field(wp_unslash($_POST['dorea_wallet_address'])))) {
                                wc_add_notice(esc_html__('Please enter a valid wallet address!', 'crypto-dorea-crypto-cashback-for-woocommerce'), 'error');
                            }
                        }
                    }

                    if(isset($_POST['dorea_wallet_address'])) {
                        if (!empty(sanitize_text_field(wp_unslash($_POST['dorea_wallet_address'])))) {
                            if (substr(sanitize_text_field(wp_unslash($_POST['dorea_wallet_address'])), 0, 2) !== '0x') {
                                wc_add_notice(esc_html__('Wallet Address must start with 0x!', 'crypto-dorea-crypto-cashback-for-woocommerce'), 'error');
                            } elseif (strlen(sanitize_text_field(wp_unslash($_POST['dorea_wallet_address']))) < 42) {
                                wc_add_notice(esc_html__('Please enter a valid wallet address!', 'crypto-dorea-crypto-cashback-for-woocommerce'), 'error');
                            }
                            if (!in_array(true, $checkoboxes)) {
                                wc_add_notice(esc_html__('Please choose at least one campaign!', 'crypto-dorea-crypto-cashback-for-woocommerce'), 'error');
                            }
                        }
                    }

                }
            }
        }
    }
}

/**
 * Update the order meta with field value
 */
add_action( 'woocommerce_checkout_update_order_meta', 'dorea_update_feild' );

function dorea_update_feild( $order_id ) {

    if(isset($_POST['dorea_wpnonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_POST['dorea_wpnonce']));
        if (wp_verify_nonce($nonce, 'dorea_checkout_nonce')) {

            // get cashback list of admin
            $cashback = new cashbackController();
            $cashbackList = $cashback->list();

            // get campaign list of user
            $checkoutController = new checkoutController;
            $diffCampaignsList = $checkoutController->check($cashbackList);

            if (!empty($diffCampaignsList)) {

                // show campaigns in checkout page
                if (!empty($cashbackList)) {

                    $campaignNamesJoined = [];
                    foreach ($diffCampaignsList as $campaign) {

                        $campaignInfo = get_transient('dorea_' . $campaign);

                        // check if any campaign funded or not!
                        if (get_option('dorea_' . $campaign . '_contract_address')) {
                            // check if campaign started or not
                            if ($checkoutController->expire($campaign)) {
                                if (isset($_POST[$campaignInfo['campaignName']])) {
                                    if (!empty($_POST[$campaignInfo['campaignName']])) {
                                        $campaignNamesJoined[] = sanitize_text_field(wp_unslash($campaignInfo['campaignName']));
                                    }
                                }
                            }
                        }
                    }

                }
            }
            if (!empty($_POST['dorea_wallet_address'])) {
                $order = wc_get_order($order_id);
                $order->update_meta_data('dorea_walletaddress', sanitize_text_field(wp_unslash($_POST['dorea_wallet_address'])));
                $order->save_meta_data();
            }
            if (!empty($campaignNamesJoined)) {
                $order = wc_get_order($order_id);
                $order->update_meta_data('dorea_campaigns', $campaignNamesJoined);
                $order->save_meta_data();
            }
        }
    }
}

/**
 * Crypto Cashback on Checkout View
 */
add_action('wp', 'doreaCashback', 10);
function doreaCashback(): void
{
    /**
     * load necessary libraries files
     * tailwind css v3.4.16
     * the official CDN URL: https://cdn.tailwindcss.com
     * Source code: https://github.com/tailwindlabs/tailwindcss/tree/v3.4.16
     */
    wp_enqueue_script('DOREA_CORE_STYLE', DOREA_PLUGIN_URL . 'js/tailWindCssV3416.min.js', array('jquery', 'jquery-ui-core'),
        array(),
        1,
        true
    );

    static $contractAddressConfirm;

    if(is_checkout()) {

        if (!WC()->cart->get_cart_contents_count() == 0) {

            // load claim campaign style
            wp_enqueue_style('DOREA_CHECKOUT_STYLE', DOREA_PLUGIN_URL . ('css/doreaCheckout.css'),
                array(),
                1,
            );

            // get cashback list of admin
            $cashback = new cashbackController();
            $cashbackList = $cashback->list();

            // get campaign list of user
            $checkoutController = new checkoutController;
            $diffCampaignsList = $checkoutController->check($cashbackList);

            // check on Authentication user
            if (is_user_logged_in()) {

                if ($cashbackList) {

                    // Legacy mode enabled!
                    if (has_filter('woocommerce_checkout_fields')) {

                        add_filter( 'woocommerce_form_field', 'dorea_remove_optional_checkout_fields', 9999 );
                        function dorea_remove_optional_checkout_fields( $fields) {
                            if (strpos($fields, 'id="dorea_campaigns_checkout"') !== false || strpos($fields, 'dorea-campaigns-class') !== false || strpos($fields, 'dorea_wallet_address') !== false) {
                                // Remove only <span class="optional">(optional)</span> within this context
                                $fields = preg_replace('/<span class="optional">\((optional)\)<\/span>/', '', $fields);
                            }

                            return $fields;
                        }

                        add_filter('woocommerce_after_order_notes', 'customize_checkout', 10,2);
                        function customize_checkout($checkout)
                        {
                            // get cashback list of admin
                            $cashback = new cashbackController();
                            $cashbackList = $cashback->list();

                            // get campaign list of user
                            $checkoutController = new checkoutController;
                            $diffCampaignsList = $checkoutController->check($cashbackList);
                            
                            if (!empty($diffCampaignsList)) {

                                // show campaigns in checkout page
                                if (!empty($cashbackList)) {

                                    $title = true;
                                    $campaignsList = [];
                                    foreach ($diffCampaignsList as $campaign) {

                                        $campaignInfo = get_transient('dorea_' . $campaign);

                                        // check if any campaign funded or not!
                                        if (get_option('dorea_' . $campaign . '_contract_address')) {
                                            // check if campaign started or not
                                            if ($checkoutController->expire($campaign) && $campaignInfo['mode'] === "on") {
                                                if($title){
                                                    print("
                                                        <h3 id='dorea_campaigns_checkout_title'>Join Cashback Campaigns</h3>
                                                        <div id='dorea_campaigns_checkout'>
                                                    ");
                                                }

                                                woocommerce_form_field(
                                                    $campaignInfo['campaignName'],
                                                    array(
                                                        'type' => 'checkbox',
                                                        'class' => array('dorea-campaigns-class form-row-wide'),
                                                        'label' => $campaignInfo['campaignNameLable'],
                                                        'required' => false,
                                                        'custom_attributes' => array('optional' => false)
                                                    ),
                                                    $checkout->get_value($campaignInfo['campaignName'])
                                                );
                                                $campaignsList[] = true;
                                                if($title){
                                                    print('</div>');
                                                    $title = false;
                                                }
                                            }
                                        }
                                    }
                                    if(in_array(true, $campaignsList)) {
                                        woocommerce_form_field(
                                            'dorea_wallet_address',
                                            array(
                                                'type' => 'text',
                                                'class' => array('dorea-wallet-address-class form-row-wide'),
                                                'label' => 'wallet address', 'crypto-dorea-crypto-cashback-for-woocommerce',
                                                'placeholder' => __('Enter Wallet Address...', 'crypto-dorea-crypto-cashback-for-woocommerce'),
                                                'required' => false,
                                            ),
                                            $checkout->get_value('dorea_wallet_address')
                                        );
                                    }

                                    woocommerce_form_field(
                                        'dorea_wpnonce',
                                        array(
                                            'type' => 'hidden',
                                            'required' => false,
                                            'default' => wp_create_nonce('dorea_checkout_nonce'),
                                        ),
                                        $checkout->get_value('dorea_wpnonce')
                                    );

                                }
                            }

                        }

                    }
                    // HPO mode enabled!
                    else {
                        $mode = null;
                        if (!empty($diffCampaignsList)) {

                            $addtoCashback = true;

                            // show campaigns in checkout page
                            if (!empty($cashbackList)) {

                                foreach ($diffCampaignsList as $campaign) {

                                    $campaignInfo = get_transient('dorea_' . $campaign);

                                    // check if any campaign funded or not!
                                    if (get_option('dorea_' . $campaign . '_contract_address')) {

                                        $contractAddressConfirm = true;
                                        $mode = $campaignInfo['mode'];

                                        // check if campaign started or not
                                        if ($checkoutController->expire($campaign) && $campaignInfo['mode'] === "on") {

                                            // add to cash back program option
                                            if ($addtoCashback) {
                                                print("<div id='doreaOpen' class='!fixed xl:!left-auto lg:!left-auto md:!left-auto sm:!left-0 !left-0 !right-0 xl:!w-96 lg:!w-96 md:!w-96 sm:!w-screen !w-screen !bottom-[0%] !pr-2 !pb-2'>
                                                       <svg id='doreaOpenIcon' xmlns='http://www.w3.org/2000/svg' class='size-7 !cursor-pointer !float-right' viewBox='0 0 576 512'><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d='M512 80c8.8 0 16 7.2 16 16l0 32L48 128l0-32c0-8.8 7.2-16 16-16l448 0zm16 144l0 192c0 8.8-7.2 16-16 16L64 432c-8.8 0-16-7.2-16-16l0-192 480 0zM64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zm56 304c-13.3 0-24 10.7-24 24s10.7 24 24 24l48 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-48 0zm128 0c-13.3 0-24 10.7-24 24s10.7 24 24 24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-112 0z'/></svg>                           
                                                   </div>
                                                   <div id='doreaCheckout' class='!fixed xl:!left-auto lg:!left-auto md:!left-auto sm:!left-0 !left-0 !right-0 !bottom-[0%] !bg-white xl:!w-96 lg:!w-96 md:!w-96 sm:!w-screen !w-screen  shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-7 !rounded-md !text-center !border'>
                                                       <span id='doreaClose'>
                                                           <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6 !text-rose-400 !cursor-pointer !hover:text-rose-200 !float-right'>
                                                                <path stroke-linecap='round' stroke-linejoin='round' d='M6 18 18 6M6 6l12 12' />
                                                           </svg>
                                                     </span>
                                                     <h3 class='!text-lg'>Join Cashback Campaign</h3> 
                                                     <div class='!grid !grid-cols-1 !gap-2'>
                                                         <label class='!text-sm'>Choose the campaign you wish to participate in:</label>
                                                         <div id='doreaCampaignsSection' class='!grid !grid-cols-1 !pb-5 !p-3  !w-auto !ml-1 !mr-1 !p-2 !col-span-1 !mt-2 !rounded-sm !border border-slate-700 !float-left'>");
                                                $addtoCashback = false;
                                            }

                                            echo("
                                                <div class='!flex !mt-1'>
                                                    <div class='!w-1/12 !ml-1'>
                                                        <input id='doreaaddtocashbackcheckbox_" . esc_html($campaign) . "' class='dorea_add_to_cashback_checkbox_ !accent-white !text-white !mt-1 !cursor-pointer' type='checkbox' value='" . esc_html($campaign) . "'>
                                                    </div>
                                                    
                                                    <label id='doreaaddtocashbacklabel_".esc_html($campaign)."' class='dorea_add_to_cashback_label_ !w-11/12 !pl-3 !text-left !ml-0 xl:!text-sm lg:!text-sm md:!text-sm sm:!text-sm !text-[12px] !float-left !content-center !whitespace-break-spaces !cursor-pointer'>".esc_html($campaignInfo['campaignNameLable'])."</label>
                                                    
                                                </div>
                                            ");

                                        }
                                    }
                                }
                            }
                        }
                        else {
                            print('<p id="doreaNoCampaign"></p>');
                        }

                        if ($contractAddressConfirm & $mode === "on") {
                            print('</div><div class="!col-span-1 !mt-2"><p class="!text-sm !mt-3" id="dorea_error" style="display:none;color:#ff5d5d;"></p><input class="!p-3 !text-sm !mt-1 !ml-1 !bg-white !shadow-none !rounded-md" id="dorea_walletaddress" type="text" placeholder="wallet address..."><button id="doreaChkConfirm" class="!rounded !mt-3 !pl-5 !pr-5 !pt-3 !pb-3">Join</button></div></div>');

                            $ajaxNonce = wp_create_nonce("checkout_nonce");
                            $params = array(
                                "checkoutAjaxNonce"=>$ajaxNonce,
                                'ajax_url' => admin_url('admin-ajax.php'),
                            );

                            // check and add to cash back program
                            wp_enqueue_script('DOREA_CHECKOUT_SCRIPT', DOREA_PLUGIN_URL . ('js/doreaCheckout.js'), array('jquery', 'jquery-ui-core'),
                                array(),
                                1,
                                true
                            );
                            wp_localize_script('DOREA_CHECKOUT_SCRIPT', 'param', $params);

                            // add module type to scripts
                            add_filter('script_loader_tag', 'add_type_checkout', 10, 3);
                            function add_type_checkout($tag, $handle, $src)
                            {
                                // if not your script, do nothing and return original $tag
                                if ('DOREA_CHECKOUT_SCRIPT' !== $handle) {
                                    return $tag;
                                }

                                $position = strpos($tag, 'src="') - 1;
                                // change the script tag by adding type="module" and return it.
                                $outTag = substr($tag, 0, $position) . ' type="module" ' . substr($tag, $position);

                                return $outTag;
                            }

                        }

                        print('</div>');
                    }
                }
            }
        }
    }
}

/**
 * incoming ajax handle after order received!
 */
add_action('wp_ajax_dorea_ordered_received','dorea_ordered_received');
function dorea_ordered_received()
{
    if(isset($_GET['_wpnonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
        if (isset($_POST['data']) && wp_verify_nonce($nonce, 'checkout_nonce')) {

            // get Json Data
            $json_data = sanitize_text_field(wp_unslash($_POST['data']));

            $campaignQueue = get_option('dorea_campaign_queue');

            $campaignQueue === true ? update_option('dorea_campaign_queue', $json_data) : add_option('dorea_campaign_queue', $json_data);
        }
    }
}

/**
 * callback function on order received page
 */
add_action('woocommerce_thankyou','doreaOrderReceived');
function doreaOrderReceived($orderId):void
{
   static $error;
   static $walletAddress;

   $order = json_decode(new WC_Order($orderId));

   if(isset($order->id)) {

       // get campaign info from HPO mode
       $campaignQueue = json_decode(get_option('dorea_campaign_queue'));

       // get campaign info from legacy mode
       if (!$campaignQueue) {
               foreach ($order->meta_data as $meta_data) {
                   if ($meta_data->key === 'dorea_walletaddress') {
                       $walletAddress = [
                           'walletAddress' => $meta_data->value,
                       ];
                   }
                   if ($meta_data->key === 'dorea_campaigns') {
                       $campaignlist = [
                           'campaignlists' => $meta_data->value
                       ];
                   }
               }
               if ($walletAddress) {
                   $campaignQueue = (object)array_merge($campaignlist, $walletAddress);
               }
           }

       // store new camppaigns
       if ($campaignQueue) {

               // store doreaCampaignInfo
               $checkout = new checkoutController();
               // check if campaign is expired
               $switchStatus = [];
               $statusCampaigns = [];
               if (is_array($campaignQueue->campaignlists)) {

                   $campaignLists = $campaignQueue->campaignlists;

                   foreach ($campaignLists as $campaign) {

                       $campaign = sanitize_text_field(sanitize_key($campaign));
                       $cashbackInfo = get_transient('dorea_' . $campaign) ?? null;

                       if (isset($cashbackInfo['mode'])) {
                           if ($cashbackInfo['mode'] === "on") {
                               $switchStatus[] = true;
                           } else {
                               $switchStatus[] = false;
                           }
                       } else {
                           $switchStatus[] = true;
                       }
                       $campaignLists[] = $campaign;
                       $statusCampaigns[] = $checkout->expire(sanitize_text_field(sanitize_key($campaign)));
                   }
                   if (in_array(true, $statusCampaigns) && in_array(true, $switchStatus) && isset($campaignQueue->walletAddress)) {
                       $checkout->autoRemove();
                       $checkout->checkout($campaignLists, sanitize_text_field(sanitize_key($campaignQueue->walletAddress)));
                   } else {
                       $error = true;
                   }
               }
           }

       if (!$error) {
           // receive order details
           $checkout = new checkoutController();
           $checkout->orederReceived($order);
       }

       delete_option('dorea_campaign_queue');

   }
}
