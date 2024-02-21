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

            foreach($campaignInfo as $info){

                if(!in_array($campaignInfoKeys[0],array_keys($campaignInfoList))){
                    $campaignInfoList = $campaignInfoList + $campaignInfo;
                    update_option('campaigninfo_user', $campaignInfoList);
                   break;
                }elseif( $campaignInfoList[$campaignInfoKeys[0]]['count'] !== $campaignInfo[$campaignInfoKeys[0]]['count']){
                   
                    $campaignInfoList[$campaignInfoKeys[0]]['count'] += 1;
                    update_option('campaigninfo_user', $campaignInfoList);
                    break;
                }

            }

        }else if(count($campaignInfoList) < 1){
            add_option('campaigninfo_user', $campaignInfo);  
        }

        //$_SESSION['campaignlist_user'] = null;
        
    }

}