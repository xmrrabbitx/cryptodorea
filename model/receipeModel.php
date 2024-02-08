<?php

require(WP_PLUGIN_DIR . "/dorea/abstracts/model/receipeModelAbstract.php");

/**
 * an abstract for receipe model
 */
class receipeModel extends receipeModelAbstract{


    function __construct(){

    }

    public function list(){
      
        return get_option('campaigninfo_user') !== false ? get_option('campaigninfo_user') : [];
    
    }

    public function add($campaignInfo){

        $campaignInfoList = $this->list();
        static $expDate = 12;
        if(count($campaignInfoList) > 0){
            $campaignInfoKeys = array_keys($campaignInfo);
            foreach($campaignInfoKeys as $infoKeys){

                array_filter($campaignInfoList,function($values,$keys) use(&$campaignInfoList,&$campaignInfo, &$infoKeys){
                    if($infoKeys !== $keys || $campaignInfoList[$keys]['count'] !== $campaignInfo[$infoKeys]['count']){
                        $campaignInfoList[$keys]['count'] = $campaignInfo[$infoKeys]['count'];
                        $campaignInfoList = $campaignInfoList + $campaignInfo;
                        update_option('campaigninfo_user', $campaignInfoList);
                    }

                },ARRAY_FILTER_USE_BOTH);
                
            }

        }else if(count($campaignInfoList) < 1){
            add_option('campaigninfo_user', $campaignInfo);  
        }
      
    }

 
}