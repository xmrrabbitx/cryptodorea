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

        $campaignInfo = [["campaignNames"=>$campaignNames, "walletAddress" => $userWalletAddress]];

        foreach ($campaignNames as $camps) {
            if($campaignList !== null) {
               if (!in_array($camps, $campaignList)) {
                  $result = array_merge($campaignList, $campaignInfo);
                  update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $result);
               }
            }
            else{
               add_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfo);
            }
        }
    }
}