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

    function count(){
        return get_option("campaigninfo_user");
    }

    function is_paid($order, $campaignList){

            $user = $order->get_user();
            $userName = $user->user_login;
            $displayName = $user->display_name;
            $userEmail = $user->user_email; 
            var_dump(get_option('campaigninfo_user'));
            //var_dump(delete_option('campaigninfo_user'));
           
            foreach($campaignList as $campaignName){
                
                $count = $this->count()[$campaignName]['count'] === false || null || $this->count()[$campaignName]['count'] < 1 ? 1 : $this->count()[$campaignName]['count'] + 1;
                //var_dump($count);
                // it must trigger and count campaign on every eash of product
                $campaignInfo = [$campaignName=>['username'=>$userName,'displayName'=>$displayName,'userEmail'=>$userEmail,'count'=>$count]];

                $this->receipeModel->add($campaignInfo);

            }
    }
}