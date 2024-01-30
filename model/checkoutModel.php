<?php

require(WP_PLUGIN_DIR . "/dorea/abstracts/model/checkoutModelAbstract.php");

/**
 * an abstract for checkout model
 */
class checkoutModel extends checkoutModelAbstract{


    function __construct(){

    }

    public function list(){

        return get_option('campaignList_user') !== false ? get_option('campaignList_user') : [];
    
    }

    public function add($campaignNames){

        $campaignList = $this->list();
        if(count($campaignList) > 0){
            foreach($campaignNames as $camps){
                if(!in_array($camps, $campaignList)){
                    array_push($campaignList, $camps);
                    update_option('campaignList_user', $campaignList);
                }
            }
        }else if(count($campaignList) < 1){
            add_option('campaignList_user', $campaignNames);  
        }
      
    }

 
}