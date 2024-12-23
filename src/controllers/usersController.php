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

            $campaignUser[$campaignName]['purchaseCounts'] = $campaignUser[$campaignName]['purchaseCounts'] - (int)$totalPurchases[$i];
            $campaignUser[$campaignName]['total'] = [];

            update_option('dorea_campaigninfo_user_' . $userList[$i], $campaignUser);

        }

        // add new users
        //$campaignUsers = get_option("dorea_campaigns_users_" . $campaignName);
        //$users = array_diff($campaignUsers, $userList);
        //$users = array_values($users);
       // if(!empty($users)) {
            //update_option("dorea_campaigns_users_" . $campaignName, $users);
       // }

        $claimedUsers = get_option("dorea_claimed_users_" . $campaignName);

        if($claimedUsers){
            $claimedUsers = array_merge($claimedUsers, $userList);
            $claimedUsers = array_unique($claimedUsers);
        }
        var_dump($claimedUsers);
        return get_option("dorea_claimed_users_" . $campaignName) ? update_option("dorea_claimed_users_" . $campaignName, $claimedUsers) : add_option("dorea_claimed_users_" . $campaignName, $userList);
    }
}


