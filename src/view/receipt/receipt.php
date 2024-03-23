<?php

//namespace cryptodorea\woocryptodorea\view\receipt;

/**
 * Crypto Cashback Receipt View
 */

use Cryptodorea\Woocryptodorea\controllers\receiptController;

require(WP_PLUGIN_DIR . "/woo-cryptodorea/controllers/receiptController.php");
require(WP_PLUGIN_DIR . "/woo-cryptodorea/controllers/payController.php");

add_action('woocommerce_thankyou','receipt',10,3);
function receipt($order_id){
        
    if($order_id){

        if(isset($_SESSION['campaignlist_user'])){
            // get current campaign list session
            $campaignList = $_SESSION['campaignlist_user'];
        
            // get order details of user
            $order = wc_get_order($order_id);
     
            // pass $order object into recipe controller
            $receipt = new receipt();
            $receipt->is_paid($order, $campaignList);
        }

    }
    
} 
