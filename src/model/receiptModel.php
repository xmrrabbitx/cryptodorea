<?php

namespace Cryptodorea\Woocryptodorea\model;

use Cryptodorea\Woocryptodorea\abstracts\model\receiptModelAbstract;


/**
 * an abstract for receipt model
 */
class receiptModel extends receiptModelAbstract
{

    public function list()
    {

        return get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login) !== false ? get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login) : [];

    }

    public function add($campaignInfo)
    {

        $campaignInfoList = $this->list();

        if ($campaignInfoList) {
            $campaignInfoKeys = array_keys($campaignInfo);

            update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, [$campaignInfo]);

        } else {
            var_dump("add to list");
            add_option('dorea_campaigninfo_user_'. wp_get_current_user()->user_login, $campaignInfo);
        }

    }

}