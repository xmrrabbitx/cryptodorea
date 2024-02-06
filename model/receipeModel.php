<?php

require(WP_PLUGIN_DIR . "/dorea/abstracts/model/receipeModelAbstract.php");

/**
 * an abstract for receipe model
 */
class receipeModel extends receipeModelAbstract{


    function __construct(){

    }

    public function list(){
      
        return get_transient('campaigninfo_user') !== false ? get_transient('campaigninfo_user') : [];
    
    }

    public function add($campaignInfo){
        $campaignInfoList = $this->list();
        //var_dump($campaignInfoList);
        //var_dump(get_transient('campaigninfo_user'));
        
        if(count($campaignInfoList) > 0){
            $campaignInfoKeys = array_keys($campaignInfo);
            foreach($campaignInfoKeys as $info){

                array_filter($campaignInfoList,function($values,$keys) use(&$campaignInfoList,&$campaignInfo, &$info){
                    if($info !== $keys){
                        $campaignInfoList = $campaignInfoList + $campaignInfo;
                        delete_transient('campaigninfo_user');
                        set_transient('campaigninfo_user', $campaignInfoList);
                    }

                },ARRAY_FILTER_USE_BOTH);
                
            }

        }else if(count($campaignInfoList) < 1){
            set_transient('campaigninfo_user', $campaignInfo);  
        }
      
    }

 
}