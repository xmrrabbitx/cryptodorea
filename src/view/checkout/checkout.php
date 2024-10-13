<?php

use Cryptodorea\Woocryptodorea\controllers\cashbackController;
use Cryptodorea\Woocryptodorea\controllers\checkoutController;

add_action('woocommerce_blocks_checkout_enqueue_data','cashback', 10, 3);
/**
 * Crypto Cashback Checkout View
 */
function cashback(): void
{

    if (!WC()->cart->get_cart_contents_count() == 0) {

        // get cashback list of admin
        $cashback = new cashbackController();
        $cashbackList = $cashback->list();

        // get campaign list of user
        $checkoutController = new checkoutController;

        $diffCampaignsList = $checkoutController->check($cashbackList);

        if ($cashbackList) {

            if(empty($diffCampaignsList)) {

                print ("
                    <p>You already joined all cashback programs!</p>
                ");

            }else {
                $addtoCashback = true;
                // show campaigns in view
                if (!empty($cashbackList)) {

                    foreach ($diffCampaignsList as $campaign) {
                        // check if any campaign funded or not!
                        if (get_option($campaign . '_contract_address')) {

                            // check if campaign started or not
                            if($checkoutController->expire($campaign)) {

                                // add to cash back program option
                                if ($addtoCashback) {
                                    print("
                                  <div id='add_to_cashback' style='margin-bottom:10px;padding:5px;'>
                                     <p>
                                        <h4>
                                           add to cash back program:
                                           <span>
                                               <input id='dorea_walletaddress' type='text' placeholder='your wallet address...' >
                                           </span>
                                ");
                                    $addtoCashback = false;
                                }

                                $campaignLable = explode("_", $campaign)[0];
                                print(" 
                                  <span>
                                     <label>" . $campaignLable . "</label>
                                     <input id='dorea_add_to_cashback_checkbox' class='dorea_add_to_cashback_checkbox_' type='checkbox' value='" . $campaign . "'>
                                  </span>
                               ");

                            }

                        }
                    }
                }
            }

            print('<p id="dorea_metamask_error" style="display:none;color:#ff5d5d;"></p>');

            // check and add to cash back program
            wp_enqueue_script('DOREA_CAMPAIGNCREDIT_SCRIPT',plugins_url('/woo-cryptodorea/js/checkout.js'), array('jquery', 'jquery-ui-core'));

        }
    }
}

/**
 * callback function on order received
 */
add_action('woocommerce_thankyou','orderReceived');
function orderReceived($orderId):void
{
    if (is_wc_endpoint_url('order-received')) {

        $order = json_decode(new WC_Order($orderId));

        if(isset($order->id)) {

            // send session doreaCampaignInfo to checkout controller
            wp_enqueue_script('DOREA_CHECKOUT_SCRIPT',plugins_url('/woo-cryptodorea/js/sessionCheckout.js'), array('jquery', 'jquery-ui-core'));

            // get Json Data
            $data = file_get_contents('php://input');
            $json = json_decode($data) ?? null;

            if (!empty($json)) {
                // save doreaCampaignInfo
                $checkout = new checkoutController();

                // check if campaign
                $statusCampaigns = [];
                $campaignLists = (array)$json->campaignlists;
                foreach ($campaignLists as $campaign){

                    $statusCampaigns[] = $checkout->expire($campaign);

                }
                if(in_array(true, $statusCampaigns)){
                    $checkout->autoRemove();
                    $checkout->checkout($json);
                }else{
                    wp_redirect('/');
                }
            }

            // receive order details
            $checkout = new checkoutController();
            $checkout->orederReceived($order,$orderId);
        }
    }
}
