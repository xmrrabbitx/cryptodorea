<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\autoremoveAbstract;

/**
 * Controller to autoremove cashback campaign
 */
class autoremoveController extends autoremoveAbstract
{

    function __construct()
    {


    }

    public function remove($campaignName)
    {
        $currentDateMonth = date('m');
        $cuurrentDateDay = date('d');

        foreach ($campaignName as $campaignNames){
            $campainInfo = get_transient($campaignNames);
            var_dump($campainInfo);
            if($campainInfo['startDateMonth'] <= $currentDateMonth) {
                if ($campainInfo['expDateMonth'] === $currentDateMonth) {
                    if ($campainInfo['expDateDay'] < $cuurrentDateDay) {

                        $cashback = new cashbackController();
                        $cashback->remove($campaignNames);

                    }
                } else {
                    if ($campainInfo['expDateMonth'] < $currentDateMonth) {
                        $cashback = new cashbackController();
                        $cashback->remove($campaignNames);
                    }
                }
            }

        }
    }

}
