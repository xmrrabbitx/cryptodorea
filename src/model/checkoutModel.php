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

        return get_option('campaignlist_user') !== false ? get_option('campaignlist_user') : [];

    }

    public function add($campaignNames)
    {

        $campaignList = $this->list();
        if (count($campaignList) > 0) {
            foreach ($campaignNames as $camps) {
                if (!in_array($camps, $campaignList)) {
                    array_push($campaignList, $camps);
                    update_option('campaignlist_user', $campaignList);
                }
            }
        } else if (count($campaignList) < 1) {
            add_option('campaignlist_user', $campaignNames);
        }

        // set flash list of current user campaigns
        $_SESSION['campaignlist_user'] = $campaignNames;

    }


}