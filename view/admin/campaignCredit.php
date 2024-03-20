<?php

/**
 * Crypto Cashback Campaign Credit
 */
require(WP_PLUGIN_DIR . "/woo-cryptodorea/controllers/campaignCreditController.php");
require(WP_PLUGIN_DIR . "/woo-cryptodorea/controllers/web3/smartContract.php");


function dorea_cashback_campaign_credit()
{
if(is_page('credit')){
    die("credit page");
}
    if(is_page('credit&tab=1')){
        die("tab 1");
    }
    print("campaign credit page");
    print("
    
        <form method='POST' action='".esc_url(admin_url('admin-post.php'))."' id='campaign_credit'>
             <input type='hidden' name='action' value='campaign_credit'>
            <input type='text' name='amount'>
            <button type='submit'>charge</button>
        </form>
    ");


}


/**
 * Campaign Credit
 */
add_action('admin_post_campaign_credit', 'dorea_admin_campaign_credit');

function dorea_admin_campaign_credit()
{
    $doreaWeb3 = new doreaWeb3();
    $doreaWeb3->deploy();

}
