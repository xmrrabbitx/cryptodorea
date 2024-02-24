<?php

/**
 * Enqueue styles for the plugin
 */
function enqueue_custom_styles() {
    wp_enqueue_style('custom_plugin_styles', plugin_dir_url(__FILE__) . 'style/paymentModal.css');
}
// input must be after scripts
add_action('wp_enqueue_scripts', 'enqueue_custom_styles');

/**
 * a payment modal to get wallet address
 */
function paymentModal(){

    print('
        <lable>please write your wallet adddress:</lable>
        <input id="doreaModal" type="text" name="doreaModal">
    ');

}