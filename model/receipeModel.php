<?php

require(WP_PLUGIN_DIR . "/dorea/abstracts/model/receipeModelAbstract.php");

/**
 * an abstract for receipe model
 */
class receipeModel extends receipeModelAbstract{


    function __construct(){

    }

    public function list(){

        return get_transient('campaignInfo_user') !== false ? get_option('campaignList_user') : [];
    
    }

    public function add($campaignInfo){

        $campaignInfoList = $this->list();
        if(count($campaignInfoList) > 0){
            foreach($campaignInfoList as $camps){
                if(!in_array($camps, $campaignInfo)){
                    array_push($campaignList, $camps);
                    update_transient('campaignList_user', $campaignList);
                }
            }
        }else if(count($campaignInfoList) < 1){
            set_transient('campaignInfo_user', $campaignInfo);  
        }
      
    }

 
}