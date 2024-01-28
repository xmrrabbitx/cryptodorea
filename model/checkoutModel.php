<?php

require(WP_PLUGIN_DIR . "/dorea/abstracts/model/checkoutModelAbstract.php");

/**
 * an abstract for checkout model
 */
class checkoutModel extends checkoutModelAbstract{


    function __construct(){

    }

    public function list(){
       return get_option('campaignList_user') ?? null;
    }

    public function add($campaignNames){

        $campaignList = $this->list();
        if(count($campaignList) < 1){
            var_dump("triggere add");
            add_option('campaignList_user', $campaignNames);  
        }
      
    }

    public function update($campaignNames){
        $campaignList = $this->list();
        var_dump("triggere 1");
        if(count($campaignList) > 0){
            var_dump("triggere 1");
            foreach($campaignNames as $camps){
                if(!in_array($camps, $campaignList)){
                    var_dump("triggere update");
                    array_push($campaignList, $camps);
                    update_option('campaignList_user', $campaignList);
                }
            }
        }
    }
}