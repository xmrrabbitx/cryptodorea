<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\usersAbstract;

/**
 * Controller to create_modify_delete cashback campaign
 */
class usersController extends usersAbstract
{

    function remove($campaignName, array $usersList): void
    {
        array_map(function($users) use ($campaignName,&$output) {

            // remove user DB records
            $campaignInfoUser = get_option('dorea_campaigninfo_user_' . $users);

            if($campaignInfoUser){
                $i = 0;
                foreach ($campaignInfoUser as $campaigns){

                    if(in_array($campaignName, $campaigns['campaignNames'])){
                        $key = array_search($campaignName,  $campaigns['campaignNames']);
                        unset($campaigns["campaignNames"][$key]);
                        $campaignInfoUser[$i]['campaignNames'] = $campaigns["campaignNames"];
                        update_option('dorea_campaigninfo_user_' . $users, $campaignInfoUser);

                    }

                    if(empty($campaignInfoUser[$i]['campaignNames'])){
                        unset($campaignInfoUser[$i]);
                        update_option('dorea_campaigninfo_user_' . $users, $campaignInfoUser);
                    }
                    $i+=1;
                }
            }
        },$usersList);

    }

}


