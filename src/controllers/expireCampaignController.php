<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\expireCampaignAbstract;
use Cryptodorea\Woocryptodorea\utilities\dateCalculator;


class expireCampaignController extends expireCampaignAbstract
{

    public function check(int $timestamp): bool
    {
        $datecCalculator = new dateCalculator();

        $currentTime = $datecCalculator->currentDate();

        if($currentTime >= $timestamp){
            return true;
        }else{
            return false;
        }
    }

}