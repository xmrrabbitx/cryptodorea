<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\paymentAbstract;


class paymentController extends paymentAbstract
{

    public function walletslist($campaignName)
    {
        $userList = get_option("dorea_campaigns_users_" . $campaignName);

        $campaignsQueue = [];
        if(!empty($userList)) {
            foreach ($userList as $users) {
                $campaigns = get_option("dorea_campaigninfo_user_" . $users);
                if($campaigns){
                    foreach ($campaigns as $campaignInfo) {
                        if (in_array($campaignName, $campaignInfo['campaignNames'])) {
                            $campaignsQueue[] = $campaignInfo['walletAddress'];
                        }
                    }
                }
            }
        }

        return $campaignsQueue;
    }


}