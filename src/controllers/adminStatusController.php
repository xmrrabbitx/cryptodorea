<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\adminStatusAbstract;
use Cryptodorea\Woocryptodorea\utilities\dateCalculator;

class adminStatusController extends adminStatusAbstract
{

    function __construct()
    {


    }

    public function set($timestamp)
    {
        if(!empty($timestamp)){
            if(!get_option('adminPaymentTimestamp')){
                    add_option('adminPaymentTimestamp', $timestamp);
            }else{
                update_option('adminPaymentTimestamp', $timestamp);
            }
        }
    }

    public function paid()
    {
        $date = new dateCalculator();
        $currentTimestamp = $date->currentDate();

        if($currentTimestamp >= ((int)get_option('adminPaymentTimestamp'))){
            return false;
        }

        return true;
    }

}