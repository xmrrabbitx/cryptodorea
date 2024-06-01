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
 * a payment modal to get wallet address and pay to user
 */
add_action('wp', 'paymentModal');
function paymentModal(){

    //var_dump(get_option('dorea_campaigninfo_user'));
    //var_dump(get_transient('dorea'));

    $campaignInfoUser = get_option('dorea_campaigninfo_user');

    foreach ($campaignInfoUser as $keys=>$values){
        $campaign = get_transient($keys);

        if($values['count'] >= $campaign['shoppingCount']){
            // Payment Modal
            return print('
                <div id="doreaPaymentModalContainer" class="dorea-payment-modal-container">
                    <form method="POST" action="#">
                        <lable>please write your wallet adddress:</lable>
                        <input style="" id="doreaModalText" type="text" name="dorea-modal-text">
                        <button type="submit">submit</button>
                    </form>
                </div>
            ');
        }
    }
}