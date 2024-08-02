<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\paymentAbstract;


class paymentController extends paymentAbstract
{

    public function list($campaignName)
    {
        $userList = get_option("dorea_campaigns_users");

        $campaignsQueue = [];
        foreach ($userList as $users){

            $campaigns = get_option("dorea_campaigninfo_user_" . $users);
            foreach ($campaigns as $campaignInfo){
                if(in_array($campaignName, $campaignInfo['campaignNames'])){
                    $campaignsQueue[] = $campaignInfo['walletAddress'];
                }
            }

        }

        var_dump($campaignsQueue);
        return $campaignsQueue;
    }

}