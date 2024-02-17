<?php

/**
 * Controller to create_modify_delete cashback campaign
 */

require(WP_PLUGIN_DIR . "/dorea/abstracts/cashbackAbstract.php");
require(WP_PLUGIN_DIR . "/dorea/utilities/expCalculator.php");
require(WP_PLUGIN_DIR . "/dorea/utilities/dateCalculator.php");

class cashback extends cashbackAbstract{

    function __contruct(){


    }

    
    public function create($campaignName, $cryptoType, $cryptoAmount,  $shoppingCount, $startDate, $expDate){

      $arr = ['campaignName' => $campaignName, 'cryptoType' => $cryptoType, 'cryptoAmount' => $cryptoAmount, 'shoppingCount' => $shoppingCount, 'startDate' => $startDate, 'expDate' => $expDate];
      
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
         return get_option('campaign_list');
      }

    }

    public function addtoList($campaignName){

      if(!empty($campaignName)){

          $list = get_option('campaign_list');

          if($list){
              array_push($list, $campaignName);
              update_option('campaign_list', $list);
          }else{
              $list = [$campaignName];
              add_option('campaign_list', $list);
          }
          
      }

    }

    public function modify(){

    }

    public function check($campaignName){

      
    }

    public function remove($campaignName){

      if(!empty($campaignName)){
         
         $campaignList = get_option('campaign_list');
         $campaignModified = array_filter($campaignList,function($list) use($campaignName){
            return $list !== $campaignName;
         });
       
         update_option('campaign_list', $campaignModified);
         delete_transient($campaignName);

      }
    
    }

 }