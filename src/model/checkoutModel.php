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

        return get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login) !== false ? get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login) : null;

    }

    public function add($campaignNames, $userWalletAddress)
    {

        // add/update campaigns info user
        $campaignList = $this->list();
        $campaignsAmin = get_option("campaign_list");

        $campaignInfo = [["campaignNames"=>$campaignNames, "walletAddress" => $userWalletAddress]];

        if($campaignsAmin) {
            foreach ($campaignNames as $camps) {
                if (in_array($camps, $campaignsAmin)) {

                    if ($campaignList['campaignNames'] !== null) {

                        if (!in_array($camps, $campaignList['campaignNames'])) {

                            $result = array_merge($campaignList, $campaignInfo);
                            update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $result);
                        }

                    } else {
                        add_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfo);
                    }
                }
            }
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