<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\usersAbstract;

/**
 * Controller to create_modify_delete cashback campaign
 */
class usersController extends usersAbstract
{

    function is_paid($campaignName, array $usersList, array $amount, array $totalPurchases): void
    {

        $i = 0;
        foreach ($usersList as $users){

            $campaignUser = get_option('dorea_campaigninfo_user_' . $users);

            $campaignUser[$campaignName]['claimedReward'] = $amount[$i];
            $campaignUser[$campaignName]['purchaseCounts'] = $campaignUser[$campaignName]['purchaseCounts'] - (int)$totalPurchases[$i];
            $campaignUser[$campaignName]['total'] = [];

            update_option('dorea_campaigninfo_user_' . $users, $campaignUser);

            $i += 1;
        }
    }
}


