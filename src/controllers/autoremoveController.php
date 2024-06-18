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
        $currentDate = date('m');

    }

}
