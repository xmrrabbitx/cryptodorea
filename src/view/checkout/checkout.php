<?php

use Cryptodorea\DoreaCashback\controllers\cashbackController;
use Cryptodorea\DoreaCashback\controllers\checkoutController;


add_action( 'woocommerce_checkout_process', 'dorea_checkout_field_process',9999 );
function dorea_checkout_field_process() {
var_dump("okkk");
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

                $campaignInfo = get_transient($campaign);

                // check if any campaign funded or not!
                if (get_option($campaign . '_contract_address')) {
                    // check if campaign started or not
                    if ($checkoutController->expire($campaign)) {

                        if ($_POST[$campaignInfo['campaignNameLable']]) {
                           $checkoboxes[] = true;
                        }
                    }
                }
            }

            if(in_array(true, $checkoboxes)) {
                if (!$_POST['dorea_wallet_address']) {

                   wc_add_notice(esc_html__('Please enter a valid wallet address!'), 'error');

                }
            }else {
                wc_add_notice(esc_html__('Please choose at least one campaign!'), 'error');
            }
            if($_POST['dorea_wallet_address']){
                if(substr($_POST['dorea_wallet_address'], 0,2) !== '0x'){
                    wc_add_notice(esc_html__('Wallet Address must start with 0x !'), 'error');
                }elseif (strlen($_POST['dorea_wallet_address']) < 42){
                    wc_add_notice(esc_html__('Please enter a valid wallet address!'), 'error');
                }
            }
        }
    }
}

/**
 * Crypto Cashback Checkout View
 */
add_action('wp', 'cashback', 10);
function cashback(): void
{
    static $contractAddressConfirm;


    if(is_checkout()) {

        if (!WC()->cart->get_cart_contents_count() == 0) {

            // load claim campaign style
            wp_enqueue_style('DOREA_CHECKOUT_STYLE', plugins_url('/cryptodorea/css/checkout.css'));

            // get cashback list of admin
            $cashback = new cashbackController();
            $cashbackList = $cashback->list();

            // get campaign list of user
            $checkoutController = new checkoutController;
            $diffCampaignsList = $checkoutController->check($cashbackList);

            // check on Authentication user
            if (is_user_logged_in()) {

                if ($cashbackList) {

                    if (has_filter('woocommerce_checkout_fields')) {

                        // check and add to cash back program
                        wp_enqueue_script('DOREA_CHECKOUTLEGACY_SCRIPT', plugins_url('/cryptodorea/js/checkoutLegacy.js'), array('jquery', 'jquery-ui-core'));

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

                                    print("
                                        <h3 id='dorea_campaigns_checkout_title'>Join to Cashback Campaigns</h3>
                                        <div id='dorea_campaigns_checkout'>
                                    ");
                                    foreach ($diffCampaignsList as $campaign) {

                                        $campaignInfo = get_transient($campaign);

                                        // check if any campaign funded or not!
                                        if (get_option($campaign . '_contract_address')) {
                                            // check if campaign started or not
                                            if ($checkoutController->expire($campaign)) {
                                                woocommerce_form_field(
                                                    $campaignInfo['campaignNameLable'],
                                                    array(
                                                        'type' => 'checkbox',
                                                        'class' => array('dorea-campaigns-class form-row-wide'),
                                                        'label' => __($campaignInfo['campaignNameLable']),
                                                        'required' => false,
                                                        'custom_attributes' => array('optional' => false)
                                                    ),
                                                    $checkout->get_value($campaignInfo['campaignNameLable'])
                                                );
                                            }
                                        }
                                    }
                                    woocommerce_form_field(
                                        'dorea_wallet_address',
                                        array(
                                            'type' => 'text',
                                            'class' => array('dorea-wallet-address-class form-row-wide'),
                                            'label' => __('wallet address'),
                                            'placeholder' => __('Enter Wallet Address...'),
                                            'required' => false,
                                        ),
                                        $checkout->get_value('dorea_wallet_address')
                                    );


                                    print('</div>');
                                }
                            }

                        }

                        // legacy code here ...

                    }
                    else {

                        print("
                                        <div class='!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-7 !rounded-md !text-center !border' id='doreaCheckout' style='padding-left:10px;'>
                                            <span id='doreaClose'>
                                               <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6 !text-rose-400 !cursor-pointer !hover:text-rose-200 !float-right'>
                                                   <path stroke-linecap='round' stroke-linejoin='round' d='M6 18 18 6M6 6l12 12' />
                                               </svg>
                                           </span>
                                    ");
                        if (!empty($diffCampaignsList)) {

                            $addtoCashback = true;

                            // show campaigns in checkout page
                            if (!empty($cashbackList)) {

                                foreach ($diffCampaignsList as $campaign) {

                                    $campaignInfo = get_transient($campaign);

                                    // check if any campaign funded or not!
                                    if (get_option($campaign . '_contract_address')) {

                                        $contractAddressConfirm = true;
                                        // check if campaign started or not
                                        if ($checkoutController->expire($campaign)) {

                                            // add to cash back program option
                                            if ($addtoCashback) {
                                                print("
                                                               <h3 class='!text-lg'>Join to Cashback Campaign</h3> 
                                                               <div class='!grid !grid-cols-1 !gap-2'>
                                                                    <label class='!text-sm'>choose which campaign you want to participate in:</label>
                                                                    <div id='doreaCampaignsSection' class='!grid !grid-cols-1 !pb-5 !p-3  !w-auto !ml-1 !mr-1 !p-2 !col-span-1 !mt-2 !rounded-sm !border border-slate-700 !float-left'>
                                                            ");
                                                $addtoCashback = false;
                                            }

                                            print(" 
                                                            <div class='!flex !mt-1'>
                                                                <div class='!w-1/12 !ml-1'><input id='dorea_add_to_cashback_checkbox' class='dorea_add_to_cashback_checkbox_ !accent-white !text-white !mt-1 !cursor-pointer' type='checkbox' value='" . esc_js($campaign) . "'></div>
                                                                <label class='!w-11/12 !pl-3 !text-left !ml-0 xl:!text-sm lg:!text-sm md:!text-sm sm:!text-sm !text-[12px] !float-left !content-center !whitespace-break-spaces'>" . $campaignInfo['campaignNameLable'] . "</label>
                                                            </div>
                                                        ");

                                        }
                                    }
                                }
                            }
                        } else {
                            print('<p id="doreaNoCampaign"></p>');
                        }

                        if ($contractAddressConfirm) {
                            print('</div> 
                                                <div class="!col-span-1 !mt-2">
                                                    <p class="!text-sm !mt-3" id="dorea_error" style="display:none;color:#ff5d5d;"></p>
                                                   <input class="!p-3 !text-sm !mt-1 !ml-1 !bg-white !shadow-none !rounded-md" id="dorea_walletaddress" type="text" placeholder="wallet address...">
                                                   <button id="doreaChkConfirm" class="!rounded !mt-3 !pl-5 !pr-5 !pt-3 !pb-3">Join</button>
                                                </div> 
                                             </div>');

                        }

                        // check and add to cash back program
                        wp_enqueue_script('DOREA_CHECKOUT_SCRIPT', plugins_url('/cryptodorea/js/checkout.js'), array('jquery', 'jquery-ui-core'));

                        print('</div>');
                    }

                }
            }
        }

    }


}


/**
 * before order processed
 */
/*
add_action('woocommerce_store_api_checkout_order_processed', 'dorea_ordered_before');
function dorea_ordered_before()
{
    // check and add to cash back program
    wp_enqueue_script('DOREA_CHECKOUT_BEFORE_PROCESSED_SCRIPT', plugins_url('/cryptodorea/js/checkoutBeforeProcessed.js'), array('jquery', 'jquery-ui-core'));

}
*/

/**
 * incoming ajax handle
 */
add_action('wp_ajax_dorea_ordered_received','dorea_ordered_received');
function dorea_ordered_received()
{
    if(isset($_POST['data'])) {

        // get Json Data
        $json_data = stripslashes($_POST['data']);

        $campaignQueue = get_option('dorea_campaign_queue');

        $campaignQueue === true ? update_option('dorea_campaign_queue', $json_data) : add_option('dorea_campaign_queue', $json_data);

    }
}

/**
 * callback function on order received
 */
add_action('woocommerce_thankyou','orderReceived');
function orderReceived($orderId):void
{
        $order = json_decode(new WC_Order($orderId));

        if(isset($order->id)) {

            $campaignQueue = get_option('dorea_campaign_queue');

            if(isset($campaignQueue)) {
                if (!empty($campaignQueue)) {

                    $campaignQueue = json_decode($campaignQueue);

                    // save doreaCampaignInfo
                    $checkout = new checkoutController();

                    // check if campaign is expired
                    $statusCampaigns = [];
                    if (is_array($campaignQueue->campaignlists)) {
                        $campaignLists = $campaignQueue->campaignlists;
                        foreach ($campaignLists as $campaign) {

                            $campaign = sanitize_text_field(sanitize_key($campaign));
                            $campaignLists[] = $campaign;
                            $statusCampaigns[] = $checkout->expire(sanitize_text_field(sanitize_key($campaign)));

                        }
                        if (in_array(true, $statusCampaigns)) {
                            $checkout->autoRemove();
                            $checkout->checkout($campaignLists, sanitize_text_field(sanitize_key($campaignQueue->walletAddress)));
                        } else {
                            wp_redirect('/');
                        }
                    }
                }

                // receive order details
                $checkout = new checkoutController();
                $checkout->orederReceived($order, $orderId);

                delete_option('dorea_campaign_queue');
            }
        }
}
