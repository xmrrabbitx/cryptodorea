<?php

namespace Cryptodorea\DoreaCashback\model;

use Cryptodorea\DoreaCashback\abstracts\model\checkoutModelAbstract;

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

    public function add($campaignNames, $userWalletAddress):void
    {
        // add/update campaigns info user
        $campaignList = $this->list();
        $campaignsAdmin = get_option("dorea_campaign_list");

        $campaignInfo = [];
        if($campaignsAdmin) {

            foreach ($campaignNames as $campaign) {
                $campaignInfo[$campaign] = ["walletAddress" => $userWalletAddress];
            }

            if (!empty($campaignList)) {
                $result = array_merge($campaignList, $campaignInfo);
                update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $result);

            }else{
                add_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfo);
            }

        }
    }

    public function addUser($campaignLists):void
    {
        foreach ($campaignLists as $campaigns) {

            $userList = get_option("dorea_campaigns_users_" . $campaigns) ?? null;
            $userName = wp_get_current_user()->user_login;

            if ($userList === false) {
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