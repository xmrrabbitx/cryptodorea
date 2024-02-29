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
    
    }

    public function checkout(){
       
        // get Json Data
        $json_data = file_get_contents('php://input');
        $campaignList = json_decode($json_data);

        if(isset($campaignList)){
    
            try{
                
                $this->addtoList($campaignList['campaignlist']);

               // throw new Exception('something went wrong!');
            }catch(Exception $error){
                //
            } 
         
                
        }
         
        

    }

}
