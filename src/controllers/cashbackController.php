<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\cashbackAbstract;
use Cryptodorea\Woocryptodorea\utilities\expCalculator;


/**
 * Controller to create_modify_delete cashback campaign
 */
class cashbackController extends cashbackAbstract
{

    function __construct()
    {


    }

    public function create($campaignName, $cryptoType, $cryptoAmount, $shoppingCount, $startDateYear, $startDateMonth, $startDateDay, $expDateMonth, $expDateDay)
    {

        $expCalculator = new expCalculator();

        $arr = ['campaignName' => $campaignName, 'cryptoType' => $cryptoType, 'cryptoAmount' => $cryptoAmount, 'shoppingCount' => $shoppingCount, 'startDateYear'=>$startDateYear, 'startDateMonth' => $startDateMonth, 'startDateDay' => $startDateDay, 'expDateMonth' => $expDateMonth, 'expDateDay' => $expDateDay];

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

        if (get_option('campaign_list')) {
            return get_option('campaign_list');
        }

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

            delete_transient($campaignName);
            delete_option($campaignName . '_contract_address');

            $key = array_search($campaignName, get_option('campaign_list'));
            $campaignsList = get_option('campaign_list');
            unset($campaignsList[$key]);
            update_option('campaign_list', $campaignsList);

            if(empty(get_option('campaign_list'))){
                delete_option('campaign_list');
            }

            if(get_option('dorea_campaignlist_user')) {
                // remove dorea_campaignlist_user from list
                $key = array_search($campaignName, get_option('dorea_campaignlist_user'));
                $campaignListUser = get_option('dorea_campaignlist_user');
                unset($campaignListUser[$key]);
                update_option('dorea_campaignlist_user', $campaignListUser);

                if (empty(get_option('dorea_campaignlist_user'))) {
                    delete_option('dorea_campaignlist_user');
                }
            }

            //remove dorea_campaigninfo_user
            $campaignInfoUser = get_option('dorea_campaigninfo_user');
            if($campaignInfoUser) {
                unset($campaignInfoUser[$campaignName]);
                update_option('dorea_campaigninfo_user', $campaignInfoUser);
            }
            if(empty(get_option('dorea_campaigninfo_user'))){
                delete_option('dorea_campaigninfo_user');
            }


        }

    }

}