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
        $currentDateYear = date('Y');
        $currentDateMonth = date('m');
        $currentDateDay = date('d');

        //  add 14 days free trial
        $currentTimestamp = strtotime($currentDateDay . '.' . $currentDateMonth . '.' . $currentDateYear); //+ (14 * 86400);

        if(!get_option('trailTimestamp')){
            add_option('trailTimestamp', (int)$currentTimestamp);
        }
    }

    /**
     * expire free trial timestamp and redirect to plans page
     * @return void
     */
    public function expire()
    {
        $currentDateYear = date('Y');
        $currentDateMonth = date('m');
        $currentDateDay = date('d');

        $currentTimestamp = strtotime($currentDateDay . '.' . $currentDateMonth . '.' . $currentDateYear);

        if((int)get_option('trailTimestamp') <= $currentTimestamp){

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

        if((int)get_option('trailTimestamp') >= $currentTimestamp){

            $endDate = (new DateTime())->setTimestamp((int)get_option('trailTimestamp'));
            $currentDate = (new DateTime())->setTimestamp($currentTimestamp);

            // Calculate the difference
            $remainingDays = $endDate->diff($currentDate);

            return $remainingDays->d;
        }

        return 0;
    }
}