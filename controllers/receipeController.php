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

    function campaignInfo(){
        return get_option("campaigninfo_user");
    }

    function is_paid($order, $campaignList){

            $user = $order->get_user();
            $userName = $user->user_login;
            $displayName = $user->display_name;
            $userEmail = $user->user_email; 

            foreach($campaignList as $campaignName){

                if(isset($this->campaignInfo()[$campaignName])){
                    $count = $this->campaignInfo()[$campaignName]['count'] + 1;
                }else{
                    $count = 1;
                }
             
                //var_dump( $count );
                // it must trigger and count campaign on every eash of product
                $campaignInfo = [$campaignName=>['username'=>$userName,'displayName'=>$displayName,'userEmail'=>$userEmail,'count'=>$count]];

                $this->receipeModel->add($campaignInfo);

            }

            $pay = new pay();
            $pay->checkCount();
    }
}