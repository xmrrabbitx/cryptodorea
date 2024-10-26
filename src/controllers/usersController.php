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

            if(isset($campaignUser[$campaignName]['claimedReward'])){
                $campaignUser[$campaignName]['claimedReward'] = $campaignUser[$campaignName]['claimedReward'] + $amount[$i];
            }else{
                $campaignUser[$campaignName]['claimedReward'] = $amount[$i];
            }
          
            $campaignUser[$campaignName]['purchaseCounts'] = $campaignUser[$campaignName]['purchaseCounts'] - (int)$totalPurchases[$i];
            $campaignUser[$campaignName]['total'] = [];

            update_option('dorea_campaigninfo_user_' . $users, $campaignUser);

            $i += 1;
        }
    }

    function is_claimed($userList, $campaignName, $claimedAmount, $totalPurchases): void
    {

        for($i=0;$i<=count($userList) -1;$i++) {
            $campaignUser = get_option('dorea_campaigninfo_user_' . $userList[$i]);

            if (isset($campaignUser[$campaignName]['claimedReward'])) {
                $campaignUser[$campaignName]['claimedReward'] = $campaignUser[$campaignName]['claimedReward'] + $claimedAmount[$i];
            } else {
                $campaignUser[$campaignName]['claimedReward'] = $claimedAmount[$i];
            }

            $campaignUser[$campaignName]['purchaseCounts'] = $campaignUser[$campaignName]['purchaseCounts'] - (int)$totalPurchases[$i];
            $campaignUser[$campaignName]['total'] = [];
var_dump($campaignUser);
            update_option('dorea_campaigninfo_user_' . $userList[$i], $campaignUser);

        }

    }


}


