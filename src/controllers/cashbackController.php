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

        $encrypt = new Encrypt();
        $encryptedSecretHash = $encrypt->randomSha256();
        $encryptedInitKey = $encrypt->randomSha256();

        $expCalculator = new expCalculator();

        $arr = ['campaignName' => $campaignName, 'cryptoType' => $cryptoType, 'cryptoAmount' => $cryptoAmount, 'shoppingCount' => $shoppingCount, 'startDateYear'=>$startDateYear, 'startDateMonth' => $startDateMonth, 'startDateDay' => $startDateDay, 'expDateMonth' => $expDateMonth, 'expDateDay' => $expDateDay, "secretHash" => $encryptedSecretHash, "initKey" => $encryptedInitKey, 'timestamp' => $timestamp];

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

        return get_option('campaign_list') !== false ? get_option('campaign_list') : false;

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

    public function remove($campaignName)
    {

        if (!empty($campaignName)) {

            // remove admin DB records
            delete_transient($campaignName);
            delete_option($campaignName . '_contract_address');

            $key = array_search($campaignName, get_option('campaign_list'));
            $campaignsList = get_option('campaign_list');
            unset($campaignsList[$key]);
            update_option('campaign_list', $campaignsList);

            if(empty(get_option('campaign_list'))){
                delete_option('campaign_list');
            }

            // remove user DB records
            $campaignInfoUser = get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login);

            if($campaignInfoUser){
                $i = 0;
                foreach ($campaignInfoUser as $campaigns){

                    if(in_array($campaignName, $campaigns['campaignNames'])){
                        $key = array_search($campaignName,  $campaigns['campaignNames']);
                        unset($campaigns["campaignNames"][$key]);
                        $campaignInfoUser[$i]['campaignNames'] = $campaigns["campaignNames"];
                        update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfoUser);

                    }

                    if(empty($campaignInfoUser[$i]['campaignNames'])){
                        unset($campaignInfoUser[$i]);
                        update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfoUser);
                    }
                    $i+=1;
                }
            }

            if(empty(get_option('dorea_campaigninfo_user_'. wp_get_current_user()->user_login))){
                delete_option('dorea_campaigninfo_user_'. wp_get_current_user()->user_login);
            }


            $queueDeleteCampaigns = get_option('dorea_queue_delete_campaigns');

            if($queueDeleteCampaigns === false){
                add_option("dorea_queue_delete_campaigns", [$campaignName]);
            }else{
                if(!in_array($campaignName, $queueDeleteCampaigns)) {
                    $queueDeleteCampaigns[] = $campaignName;
                    update_option("dorea_queue_delete_campaigns", $queueDeleteCampaigns);
                }
            }

            $campaignUsers = get_option("dorea_campaigns_users_" . $campaignName);
            if($campaignUsers){
                delete_option("dorea_campaigns_users_" . $campaignName);
            }
        }
    }

}