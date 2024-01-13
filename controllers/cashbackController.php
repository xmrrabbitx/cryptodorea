<?php

/**
 * Controller to create_modify_delete cashback campaign
 */

require(WP_PLUGIN_DIR . "/dorea/abstracts/cashbackAbstract.php");
require(WP_PLUGIN_DIR . "/dorea/utility/expCalculator.php");

 class cashback extends cashbackAbstract{

    function __contruct(){


    }

    
    public function create($campaignName,$cryptoType,$startDate,$expDate){

      $arr = [$campaignName,$cryptoType,$startDate,$expDate];
      
         if(empty($this->list()) || !in_array($campaignName, $this->list())){
            if(isset($arr)){
               $exp = expCalculator($expDate);
               set_transient($campaignName, $arr, $exp);
               $this->addtoList($campaignName);
            }
         }
      

    }

    public function list(){

      if(get_option('campaign_list')){
         //var_dump(get_option('campaign_list'));
         return get_option('campaign_list');
      }

    }

    public function addtoList($campaignName){

      if(!empty($campaignName)){
          $list = get_option('campaign_list');

     
          if($list){
            array_push($list, $campaignName);
            var_dump($list);
            update_option('campaign_list', $list);
           
          }else{
            var_dump("trigger 2");
            $list = [$campaignName];
            add_option('campaign_list', $list);
          }
         

      }

    }

    public function modify(){

    }

    public function remove(){

    }

 }