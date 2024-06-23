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

        $cuurrentDateYear = date('Y');
        $currentDateMonth = date('m');
        $cuurrentDateDay = date('d');

        $cashback = new cashbackController();

        if($campaignName) {
            foreach ($campaignName as $campaignNames) {
                $campainInfo = get_transient($campaignNames);

                // check on Year expiration
                if($campainInfo['startDateYear'] < $cuurrentDateYear){
                    $cashback->remove($campaignNames);
                }

                // check on Month and Day expiration
                if ($campainInfo['startDateMonth'] <= $currentDateMonth) {
                    if ($campainInfo['expDateMonth'] === $currentDateMonth) {
                        if ($campainInfo['expDateDay'] < $cuurrentDateDay) {

                            $cashback->remove($campaignNames);

                        }
                    } else {
                        if ($campainInfo['expDateMonth'] < $currentDateMonth) {

                            $cashback->remove($campaignNames);
                        }
                    }
                }

            }
        }

    }

}
