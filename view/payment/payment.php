<?php

/**
 * Enqueue styles for the plugin
 */
function paymentModal_styles() {
    wp_enqueue_style('dorea_payment_modal_styles', plugin_dir_url('dorea/view') . 'view/style/paymentModal.css');
}
// input must be after scripts
add_action('wp_enqueue_scripts', 'paymentModal_styles');

/**
 * a payment modal to get wallet address
 */
function paymentModal(){

    print('
        <div id="doreaPaymentModalContainer" class="dorea-payment-modal-container">
            <form method="POST" action="#">
                <lable>please write your wallet adddress:</lable>
                <input style="" id="doreaModalText" type="text" name="dorea-modal-text">
                <button type="submit">submit</button>
            </form>
        </div>
    ');

}