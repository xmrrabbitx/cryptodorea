<?php

namespace Cryptodorea\DoreaCashback\model;

use Cryptodorea\DoreaCashback\abstracts\model\receiptModelAbstract;

/**
 * an abstract for receipt model
 */
class receiptModel extends receiptModelAbstract
{
    public function list()
    {
        return get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login) !== false ? get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login) : [];
    }

    public function add($campaignInfo):void
    {
        $campaignInfoList = $this->list();

        if ($campaignInfoList) {

            update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfo);

        } else {
            // actually do nothing because add before in checkout step
            add_option('dorea_campaigninfo_user_'. wp_get_current_user()->user_login, $campaignInfo);
        }
    }
}