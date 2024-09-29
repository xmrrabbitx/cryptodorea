<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\adminStatusAbstract;
use Cryptodorea\Woocryptodorea\utilities\dateCalculator;

class adminStatusController extends adminStatusAbstract
{
    public function set($timestamp)
    {

        return get_option('adminPaymentTimestamp') ? update_option('adminPaymentTimestamp', $timestamp) : add_option('adminPaymentTimestamp', $timestamp);

    }

    public function is_paid():bool
    {
        $date = new dateCalculator();
        $currentTimestamp = $date->currentDate();

        return $currentTimestamp >= (int)get_option('adminPaymentTimestamp') ?? false;
    }

}