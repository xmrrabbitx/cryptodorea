<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\cashbackAbstract;
use Cryptodorea\Woocryptodorea\utilities\Encrypt;
use Cryptodorea\Woocryptodorea\utilities\expCalculator;


/**
 * Controller to create_modify_delete cashback campaign
 */
class cashbackController extends cashbackAbstract
{

    public function create($campaignName, $cryptoType, $cryptoAmount, $shoppingCount, $startDateYear, $startDateMonth, $startDateDay, $expDateMonth, $expDateDay, $timestamp)
    {

        $expCalculator = new expCalculator();

        $arr = ['campaignName' => $campaignName, 'cryptoType' => $cryptoType, 'cryptoAmount' => $cryptoAmount, 'shoppingCount' => $shoppingCount, 'startDateYear'=>$startDateYear, 'startDateMonth' => $startDateMonth, 'startDateDay' => $startDateDay, 'expDateMonth' => $expDateMonth, 'expDateDay' => $expDateDay, 'timestamp' => $timestamp];

        if (empty($this->list()) || !in_array($campaignName, $this->list())) {
            if (isset($arr)) {

                $exp = $expCalculator->expTime($expDateDay);
                set_transient($campaignName, $arr, $exp);
                $this->addtoList($campaignName);

            }
        }

    }

    public function list()
    {

        return get_option('campaign_list');

    }

    public function addtoList($campaignName)
    {

        if (!empty($campaignName)) {

            $list = get_option('campaign_list');

            if ($list) {
                array_push($list, $campaignName);
                update_option('campaign_list', $list);
            } else {
                $list = [$campaignName];
                add_option('campaign_list', $list);
            }

        }

    }

    public function modify()
    {

    }

    public function check($campaignName)
    {


    }

    /*
     * remove admin Database records
     */
    public function remove($campaignName):void
    {

        if (!empty($campaignName)) {

            delete_transient($campaignName); // remove campaign information
            delete_option($campaignName . '_contract_address'); // remove campaign contract address

            // remove or update campaign_list
            if($this->list()) {
                $key = array_search($campaignName, $this->list());
                $campaignsList = get_option('campaign_list');
                unset($campaignsList[$key]);
                update_option('campaign_list', $campaignsList);
            }
            if(empty(get_option('campaign_list'))){
                delete_option('campaign_list');
            }


            // remove dorea_campaigninfo_user_
            $campaignInfoUser = get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login);
            if($campaignInfoUser){
                $campaignInfoUserKeys = array_keys($campaignInfoUser);
                if(in_array($campaignName,$campaignInfoUserKeys)){
                    unset($campaignInfoUser[$campaignName]);
                    update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfoUser);
                    var_dump($campaignInfoUser);
                }
            }
            if(empty(get_option('dorea_campaigninfo_user_'. wp_get_current_user()->user_login))){
                delete_option('dorea_campaigninfo_user_'. wp_get_current_user()->user_login);
            }

            // add to delete queue
            $queueDeleteCampaigns = get_transient('dorea_queue_delete_campaigns');
            if($queueDeleteCampaigns === false){
                set_transient("dorea_queue_delete_campaigns", [$campaignName], 604800); //set weekly expiration
            }else{
                if(!in_array($campaignName, $queueDeleteCampaigns)) {
                    $queueDeleteCampaigns[] = $campaignName;
                    set_transient("dorea_queue_delete_campaigns", $queueDeleteCampaigns); //set weekly expiration
                }
            }

            // remove campaign users list
            $campaignUsers = get_option("dorea_campaigns_users_" . $campaignName);
            if($campaignUsers){
                delete_option("dorea_campaigns_users_" . $campaignName);
            }

        }
    }

}