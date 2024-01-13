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
           
            <lable>campaign name</lable>
            <input type='text' name='campaignName'>
            </br>

            <lable>crypt type</lable>
            <input type='text' name='cryptoType'>
            </br>

            <lable>start date</lable>
            <input type='text' name='startDate'>
            </br>

            <lable>expire date</lable>
            <input type='text' name='expDate'>
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

    if(!empty($_POST['campaignName'] && $_POST['cryptoType'] && $_POST['startDate'] && $_POST['expDate'])){

            $campaignName = htmlspecialchars($_POST['campaignName']);
            $cryptoType = htmlspecialchars($_POST['cryptoType']);
            $startDate = htmlspecialchars($_POST['startDate']);
            $expDate = htmlspecialchars($_POST['expDate']);

            $cashback = new cashback();
            //delete_option('campaign_list');
            //add_option('campaign_list', ['digi']);
            $cashback->create($campaignName, $cryptoType, $startDate, $expDate);
            
            //var_dump($cashback->list());
    }    
    
 

}