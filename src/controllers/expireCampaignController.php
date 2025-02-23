<?php

namespace Cryptodorea\DoreaCashback\controllers;

use Cryptodorea\DoreaCashback\abstracts\expireCampaignAbstract;
use Cryptodorea\DoreaCashback\utilities\dateCalculator;

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