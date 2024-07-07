<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\freetrialAbstract;

/**
 * Controller to freetrial Controller
 */
class freetrialController extends freetrialAbstract
{

    function __construct()
    {


    }

    public function set()
    {
        $currentDateYear = date('Y');
        $currentDateMonth = date('m');
        $currentDateDay = date('d');

        $currentTimestamp = strtotime($currentDateDay . '.' . $currentDateMonth . '.' . $currentDateYear);

        if(!get_option('trailTimestamp')){
            add_option('trailTimestamp', (int)$currentTimestamp);
        }
    }

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
}