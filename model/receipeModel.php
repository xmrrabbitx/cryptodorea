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
            //var_dump($campaignInfo);
            //var_dump(array_keys($campaignInfoList));
            foreach($campaignInfo as $info){
                //var_dump($campaignInfoKeys[0]);
                if(!in_array($campaignInfoKeys[0],array_keys($campaignInfoList))){
                    $campaignInfoList = $campaignInfoList + $campaignInfo;
                    //var_dump($campaignInfoList);
                    update_option('campaigninfo_user', $campaignInfoList);
                    return true;
                }elseif( $campaignInfoList[$campaignInfoKeys[0]]['count'] !== $campaignInfo[$campaignInfoKeys[0]]['count']){
                   
                    $campaignInfoList[$campaignInfoKeys[0]]['count'] += 1;
                    //var_dump($campaignInfoList);
                    update_option('campaigninfo_user', $campaignInfoList);
                    //var_dump($campaignInfoList[$campaignInfoKeys[0]]['count']);
                    return true;
                }
/*
                if($campaignInfoKeys[0] !== $infoListKeys || $campaignInfoList[$infoListKeys]['count'] !== $campaignInfo[$campaignInfoKeys[0]]['count']){
                    //$campaignInfoList[$infoListKeys]['count'] = $campaignInfo[$campaignInfoKeys[0]]['count'];
                    //$campaignInfoList = $campaignInfoList + $campaignInfo;
                    //update_option('campaigninfo_user', $campaignInfoList);
                    return true;
                }
*/
            }
            
           // foreach($campaignInfoKeys as $infoKeys){
              
                /*
                array_filter($campaignInfoList,function($values,$keys) use(&$campaignInfoList,&$campaignInfo, &$infoKeys){
                    if($infoKeys !== $keys || $campaignInfoList[$keys]['count'] !== $campaignInfo[$infoKeys]['count']){
                        $campaignInfoList[$keys]['count'] = $campaignInfo[$infoKeys]['count'];
                        var_dump($campaignInfoList[$keys]['count']);
                        $campaignInfoList = $campaignInfoList + $campaignInfo;
                        //update_option('campaigninfo_user', $campaignInfoList);
                    }

                },ARRAY_FILTER_USE_BOTH);
                */
            //}
            
        }else if(count($campaignInfoList) < 1){
            add_option('campaigninfo_user', $campaignInfo);  
        }
      
    }

 
}