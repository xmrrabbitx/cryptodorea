<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\freetrialAbstract;
use Cryptodorea\Woocryptodorea\utilities\dateCalculator;
use DateTime;

/**
 * Controller to freetrial Controller
 */
class freetrialController extends freetrialAbstract
{

    function __construct()
    {


    }

    /**
     * set free trial timestamp
     * @return void
     */
    public function set()
    {
        $date = new dateCalculator();
        $currentTimestamp = $date->currentDate();

        // it must add + 14 * 86400 (14 days free trail days)
        if(!get_option('trialTimestamp')){
            add_option('trialTimestamp', $currentTimestamp + (14 * 86400) );
        }
    }

    /**
     * expire free trial timestamp and redirect to plans page
     * @return void
     */
    public function expire()
    {
        $date = new dateCalculator();
        $currentTimestamp = $date->currentDate();

        if((int)get_option('trialTimestamp') <= $currentTimestamp){

            // remove wordpress prefix on production
            wp_redirect('admin.php?page=dorea_plans');

        }
    }

    /**
     * calculate remain days of free trial period
     */
    public function remainedDays():int
    {
        $date = new dateCalculator();
        $currentTimestamp = $date->currentDate();

        if((int)get_option('trialTimestamp') >= $currentTimestamp){

            $endDate = (new DateTime())->setTimestamp((int)get_option('trialTimestamp'));
            $currentDate = (new DateTime())->setTimestamp($currentTimestamp);

            // Calculate the difference
            $remainingDays = $endDate->diff($currentDate);

            return $remainingDays->d;
        }

        return 0;
    }
}