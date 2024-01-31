<?php

/**
 * Crypto Cashback Receipe View
 */

require(WP_PLUGIN_DIR . "/dorea/controllers/receipeController.php");

add_action('woocommerce_thankyou','receipe',10,3);
function receipe($order_id){ 

    if($order_id){

        // get order details of user
        $order = wc_get_order($order_id);

        // pass $order object into recipe controller
        $receipe = new receipe();
        $receipe->is_paid($order);

    }
    
} 
