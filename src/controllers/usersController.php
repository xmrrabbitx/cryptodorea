<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\usersAbstract;

/**
 * Controller to create_modify_delete cashback campaign
 */
class usersController extends usersAbstract
{

    function paid($campaignName, array $usersList, array $amount): void
    {

        $i = 0;
        foreach ($usersList as $users){

            $campaignUser = get_option('dorea_campaigninfo_user_' . $users);

            $campaignUser['claimedReward'] = $amount[$i];

            update_option('dorea_campaigninfo_user_' . $users, $campaignUser);

            $i += 1;
        }

    }

}


