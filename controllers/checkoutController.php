<?php

/**
 * Controller for checkout
 */

require(WP_PLUGIN_DIR . "/dorea/abstracts/checkoutAbstract.php");

class checkout extends checkoutAbstract{

    function __contruct(){

    }

    public function check($campaignNames){

        if(get_option('campaignList_user')){
            $campaignList = get_option('campaignList_user');
            foreach($campaignNames as $campaign){
                if(in_array($campaign,$campaignList)){
                    return true;
                }
            }
           
        }
  
      }

    public function add($campaignNames){

        add_option('campaignList_user', $campaignNames);
        
        /*
        if($session && $session === true){    
            //$_SESSION['cartSession'] = $session;
            $addToChProgram = $session;
            var_dump('thankyou');
            $loyaltyName = "jashnvareh2";
            $exp = 7 * 24 * 60 * 60;
            //$this->doreaDB->addtoCashBack($addToChProgram, $loyaltyName, $exp);
        }
            
        */
       
        //print($_SESSION['cartSession']);
        //print(get_transient('jashnvareh2'));
        //unset($_SESSION['cartSession']);

    }

    public function update($campaignNames){

        update_option('campaignList_user', $campaignNames);
    }

}