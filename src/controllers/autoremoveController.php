<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\autoremoveAbstract;

/**
 * Controller to autoremove cashback campaign
 */
class autoremoveController extends autoremoveAbstract
{

    public function remove($campaignName)
    {

        $currentDateYear = date('Y');
        $currentDateMonth = date('m');
        $currentDateDay = date('d');

        $currentTimestamp = strtotime($currentDateDay . '.' . $currentDateMonth . '.' . $currentDateYear);

        $cashback = new cashbackController();
        if($campaignName) {
            foreach ($campaignName as $campaignNames) {
                $campainInfo = get_transient($campaignNames);

                if($campainInfo['timestamp'] < $currentTimestamp){
                    $cashback->remove($campaignNames);
                    return true;
                }
            }
        }

        return false;

    }

}
