<?php

require(WP_PLUGIN_DIR . "/dorea/abstracts/model/receipeModelAbstract.php");

/**
 * an abstract for receipe model
 */
class receipeModel extends receipeModelAbstract{


    function __construct(){

    }

    public function list(){

        return get_transient('campaignList_user') !== false ? get_option('campaignList_user') : [];
    
    }

    public function add($campaignNames){

        $campaignList = $this->list();
        if(count($campaignList) > 0){
            foreach($campaignNames as $camps){
                if(!in_array($camps, $campaignList)){
                    array_push($campaignList, $camps);
                    update_transient('campaignList_user', $campaignList);
                }
            }
        }else if(count($campaignList) < 1){
            set_transient('campaignList_user', $campaignNames);  
        }
      
    }

 
}