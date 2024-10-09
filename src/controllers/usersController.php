<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\usersAbstract;

/**
 * Controller to create_modify_delete cashback campaign
 */
class usersController extends usersAbstract
{

    function is_paid($campaignName, array $usersList, array $claimedAmount, array $totalPurchases): void
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

    function is_claimed($campaignName, $claimedAmount, $totalPurchases): void
    {

        $campaignUser = get_option('dorea_campaigninfo_user_' .  wp_get_current_user()->user_login);

        if(isset($campaignUser[$campaignName]['claimedReward'])){
            $campaignUser[$campaignName]['claimedReward'] = $campaignUser[$campaignName]['claimedReward'] + $claimedAmount;
        }else{
            $campaignUser[$campaignName]['claimedReward'] = $claimedAmount;
        }

        $campaignUser[$campaignName]['purchaseCounts'] = $campaignUser[$campaignName]['purchaseCounts'] - (int)$totalPurchases;
        $campaignUser[$campaignName]['total'] = [];

        update_option('dorea_campaigninfo_user_' .  wp_get_current_user()->user_login, $campaignUser);


    }


}


