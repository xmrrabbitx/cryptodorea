<?php

require(WP_PLUGIN_DIR . "/dorea/abstracts/checkoutAbstract.php");
require(WP_PLUGIN_DIR . "/dorea/model/checkoutModel.php");

/**
 * Controller for checkout
 */
class checkout extends checkoutAbstract{

    function __construct(){

        $this->checkoutModel = new checkoutModel();

    }

    public function check($campaignNames){
        
        if($this->checkoutModel->list()){

            $campaignList = $this->checkoutModel->list(); 
            foreach($campaignNames as $campaign){
                if(!in_array($campaign, $campaignList)){
                    return true;
                }
            }

            return false;
        }
    }

    public function addtoList($campaignNames){

        $this->checkoutModel->add($campaignNames);
        
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

    public function checkout(){

        if(isset($_POST['campaignList'])){
    
            $campaignList = $_POST['campaignList'];
            $campaignList = explode(',',$campaignList);

            try{
                
                $this->addtoList($campaignList);

               // throw new Exception('something went wrong!');
            }catch(Exception $error){
                //
            } 
         
                
        }
         
        

    }

}
