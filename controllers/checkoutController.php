<?php

/**
 * Controller for checkout
 */

require(WP_PLUGIN_DIR . "/dorea/abstracts/checkoutAbstract.php");

class checkout extends checkoutAbstract{

    function __contruct(){

    }

    public function addtoList(){

        
        // just check if isset then we can set option for it
        if(isset($_POST['campaignList'])){
            $campaignList = $_POST['campaignList'];
            $campaignList = explode(',',$campaignList);
            update_option('campaignList_user', $campaignList);
        }
        
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

}