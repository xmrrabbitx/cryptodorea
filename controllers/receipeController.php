<?php

/**
 * a class for receipe controller
 */

require(WP_PLUGIN_DIR . "/dorea/abstracts/receipeAbstract.php");
require(WP_PLUGIN_DIR . "/dorea/model/receipeModel.php");

class receipe extends receipeAbstract{

    function __construct(){

        $this->receipeModel = new receipeModel();

    }

    function is_paid($order){

            $user = $order->get_user();
            $userName = $user->user_login;
            $displayName = $user->display_name;
            $userEmail = $user->user_email; 
            
            // it must trigger and count campaign on every eash of product
            $campaignInfo = [$productName=>['user'=>$user,'username'=>$userName,'displayName'=>$displayName,'userEmail'=>$userEmail]];

            $this->receipeModel->add($campaignInfo);
    }
}