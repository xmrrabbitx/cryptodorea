<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\paymentAbstract;


class paymentController extends paymentAbstract
{

    public function list($campaignName)
    {
        $userList = get_option("dorea_campaigns_users");
        var_dump($userList);

    }

}