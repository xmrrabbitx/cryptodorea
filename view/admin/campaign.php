<?php

/**
 * Crypto Cashback Campaign
 */

require(WP_PLUGIN_DIR . "/dorea/controllers/cashbackController.php");

function dorea_cashback_campaign_content(){

    print("campaign page");
    print("
    
        </br>
        <form method='POST' action='http:///localhost/wp-admin/admin-post.php' id='cashback_campaign'>
            
            <input type='hidden' name='action' value='cashback_campaign'>
           
            <lable>name</lable>
            <input type='text' name='campaignName'>
            </br>

            <button type='submit' onClick='setup_init_config()'>set up campaign!</button>
        
        </form>
        </br>
    
    ");
}

/**
 * set up cashback campaign
 */
add_action('admin_post_cashback_campaign', 'dorea_admin_cashback_campaign');

function dorea_admin_cashback_campaign(){

    print("yeahhhhh");

}