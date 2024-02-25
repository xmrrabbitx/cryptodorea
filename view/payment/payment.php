<?php

/**
 * Enqueue styles for the plugin
 */
function paymentModal_styles() {
    wp_enqueue_style('paymentModal_styles', plugin_dir_url(__FILE__) . 'paymentModal.css');
}
// input must be after scripts
add_action('wp_enqueue_scripts', 'paymentModal_styles');

/**
 * a payment modal to get wallet address
 */
function paymentModal(){

    print('
        <div id="customPaymentModal" class="custom-payment-modal">
            <lable>please write your wallet adddress:</lable>
            <input style="" id="doreaModal" type="text" name="doreaModal">
        </div>
    ');

}
