<?php

namespace Cryptodorea\DoreaCashback\controllers;

use Cryptodorea\DoreaCashback\abstracts\usersAbstract;

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

    function is_claimed($userList, $campaignName, $claimedAmount, $totalPurchases): bool
    {
        for($i=0;$i<=count($userList) -1;$i++) {
            $campaignUser = get_option('dorea_campaigninfo_user_' . $userList[$i]);

            if (isset($campaignUser[$campaignName]['claimedReward'])) {
                $campaignUser[$campaignName]['claimedReward'] = $campaignUser[$campaignName]['claimedReward'] + $claimedAmount[$i];
            } else {
                $campaignUser[$campaignName]['claimedReward'] = $claimedAmount[$i];
            }

            $totalCampaign = $campaignUser[$campaignName]['total'];
            $campaignUser[$campaignName]['purchaseCounts'] = $campaignUser[$campaignName]['purchaseCounts'] - (int)$totalPurchases[$i];
            array_splice($totalCampaign, 0, (int)$totalPurchases[$i] === 1 ? 1 : (int)$totalPurchases[$i] - 1);

            $campaignUser[$campaignName]['total'] = $totalCampaign;

            update_option('dorea_campaigninfo_user_' . $userList[$i], $campaignUser);

        }

        $claimedUsers = get_option("dorea_claimed_users_" . $campaignName);

        if($claimedUsers){
            $claimedUsers = array_merge($claimedUsers, $userList);
            $claimedUsers = array_unique($claimedUsers);
        }

        return get_option("dorea_claimed_users_" . $campaignName) ? update_option("dorea_claimed_users_" . $campaignName, $claimedUsers) : add_option("dorea_claimed_users_" . $campaignName, $userList);
    }
}


