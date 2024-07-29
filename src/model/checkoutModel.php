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

    public function list()
    {

        return get_option('dorea_campaignlist_user_' . wp_get_current_user()->user_login) !== false ? get_option('dorea_campaignlist_user_' . wp_get_current_user()->user_login) : [];

    }


    public function add($campaignNames)
    {

        $campaignList = $this->list();
        if (count($campaignList) > 0) {
            foreach ($campaignNames as $camps) {
                if (!in_array($camps, $campaignList)) {
                    array_push($campaignList, $camps);
                    update_option('dorea_campaignlist_user_' . wp_get_current_user()->user_login, $campaignList);
                }
            }
        } else if (count($campaignList) < 1) {
            add_option('dorea_campaignlist_user_' . wp_get_current_user()->user_login, $campaignNames);
        }

    }

}