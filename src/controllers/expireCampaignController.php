<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\expireCampaignAbstract;
use Cryptodorea\Woocryptodorea\utilities\dateCalculator;


class expireCampaignController extends expireCampaignAbstract
{


    public function check(int $timestamp)
    {
        $datecCalculator = new dateCalculator();

        $currentTime = $datecCalculator->currentDate();
;
        // remove $currentTime after test
        if($currentTime >= $currentTime){//$timestamp){
            return true;
        }else{
            return false;
        }
    }


}