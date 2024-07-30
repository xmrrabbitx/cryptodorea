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

        return get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login) !== false ? get_option('dorea_campaigninfo_user' . wp_get_current_user()->user_login) : [];

    }

    public function add($campaignInfo)
    {

        $campaignInfoList = $this->list();

        if (count($campaignInfoList) > 0) {
            $campaignInfoKeys = array_keys($campaignInfo);

            foreach ($campaignInfo as $info) {

                if (!in_array($campaignInfoKeys[0], array_keys($campaignInfoList))) {
                    $campaignInfoList = $campaignInfoList + $campaignInfo;
                    update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfoList);
                    break;
                } elseif ($campaignInfoList[$campaignInfoKeys[0]]['count'] !== $campaignInfo[$campaignInfoKeys[0]]['count']) {

                    $campaignInfoList[$campaignInfoKeys[0]]['count'] += 1;
                    update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfoList);
                    break;
                }

            }

        } else if (count($campaignInfoList) < 1) {
            add_option('dorea_campaigninfo_user_'. wp_get_current_user()->user_login, $campaignInfo);
        }

    }

}