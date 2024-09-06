<?php

namespace Cryptodorea\Woocryptodorea\model;

use Cryptodorea\Woocryptodorea\abstracts\model\checkoutModelAbstract;

/**
 * an abstract for checkout model
 */
class checkoutModel extends checkoutModelAbstract
{


    function __construct()
    {

        $_SESSION['time'] = time();

    }

    /*
     * @return campaigns list user
     */
    public function list()
    {

        return get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login) !== false ? get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login) : [];

    }

    public function add($campaignNames, $userWalletAddress)
    {

        // add/update campaigns info user
        $campaignList = $this->list();
        $campaignsAdmin = get_option("campaign_list");

        if($campaignsAdmin) {
            foreach ($campaignNames as $campaign) {

                $campaignInfo = [$campaign=>["walletAddress" => $userWalletAddress]];

                var_dump($campaignInfo);
                if (!empty($campaignList)) {
                    var_dump("update trigger");
                    $result = array_merge($campaignList, $campaignInfo);
                    update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $result);

                }else{
                    var_dump("add trigger");
                    add_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfo);

                }

            }
            /*
            foreach ($campaignNames as $camps) {
                if (in_array($camps, $campaignsAdmin)) {
                    if ($campaignList) {
                        foreach ($campaignList as $campaigns) {

                           if (!in_array($camps, $campaigns['campaignNames'])) {

                              $result = array_merge($campaignList, $campaignInfo);
                              update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $result);

                           }

                        }
                    } else {

                        add_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfo);
                    }
                }
            }
            */
        }
    }

    public function addUser($campaignLists)
    {
        foreach ($campaignLists as $campaigns) {

            $userList = get_option("dorea_campaigns_users_" . $campaigns);
            $userName = wp_get_current_user()->user_login;

            if (!$userList) {
                add_option("dorea_campaigns_users_" . $campaigns, [$userName]);
            } else {
                if (!in_array($userName, $userList)) {
                    $userList[] = $userName;
                    update_option("dorea_campaigns_users_" . $campaigns, $userList);
                }
            }
        }
    }
}