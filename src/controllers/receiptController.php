<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\model\receiptModel;
use Cryptodorea\Woocryptodorea\abstracts\receiptAbstract;

/**
 * a class for receipt controller
 */
class receiptController extends receiptAbstract
{

    function __construct()
    {

        $this->receiptModel = new receiptModel();

    }

    function campaignInfo()
    {
        return get_option("dorea_campaigninfo_user");
    }

    function is_paid($order, $campaignList)
    {

        $displayName = $order->billing->first_name . " " . $order->billing->last_name ;
        $userEmail = $order->billing->email;

        foreach ($campaignList as $campaignName) {

            if (isset($this->campaignInfo()[$campaignName])) {
                $count = $this->campaignInfo()[$campaignName]['count'] + 1;
            } else {
                $count = 1;
            }

            // it must trigger and count campaign on every each of product
            $campaignInfo = [$campaignName => ['displayName' => $displayName, 'userEmail' => $userEmail, 'count' => $count]];

            $this->receiptModel->add($campaignInfo);

        }

    }
}
