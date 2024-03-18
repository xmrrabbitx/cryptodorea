<?php

/**
 * a class for receipt controller
 */

require(WP_PLUGIN_DIR . "/woo-cryptodorea/abstracts/receiptAbstract.php");
require(WP_PLUGIN_DIR . "/woo-cryptodorea/model/receiptModel.php");

class receipt extends receiptAbstract{

    function __construct(){

        $this->receiptModel = new receiptModel();

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

                // it must trigger and count campaign on every eash of product
                $campaignInfo = [$campaignName=>['username'=>$userName,'displayName'=>$displayName,'userEmail'=>$userEmail,'count'=>$count]];

                $this->receiptModel->add($campaignInfo);

            }

            $pay = new pay();
            $pay->checkCount();
    }
}
