<?php

/**
 * Crypto Cashback Receipe View
 */

require(WP_PLUGIN_DIR . "/dorea/controllers/receipeController.php");
require(WP_PLUGIN_DIR . "/dorea/controllers/payController.php");

add_action('woocommerce_thankyou','receipe',10,3);
function receipe($order_id){ 
        
    if($order_id){

        if(isset($_SESSION['campaignlist_user'])){
            // get current campaign list session
            $campaignList = $_SESSION['campaignlist_user'];
        
            // get order details of user
            $order = wc_get_order($order_id);

            // pass $order object into recipe controller
            $receipe = new receipe();
            $receipe->is_paid($order, $campaignList);
        }

    }
    
} 
