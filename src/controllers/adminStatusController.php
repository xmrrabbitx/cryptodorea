<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\adminStatusAbstract;
use Cryptodorea\Woocryptodorea\utilities\dateCalculator;

class adminStatusController extends adminStatusAbstract
{

    public function set($timestamp):void
    {
        if(!empty($timestamp)){
            if(!get_option('adminPaymentTimestamp')){
                add_option('adminPaymentTimestamp', $timestamp);
            }else{
                update_option('adminPaymentTimestamp', $timestamp);
            }
        }
    }

    public function is_paid():bool
    {
        $date = new dateCalculator();
        $currentTimestamp = $date->currentDate();

        return $currentTimestamp >= (int)get_option('adminPaymentTimestamp') ?? false;
    }

}