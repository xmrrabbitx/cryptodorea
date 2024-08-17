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
        return get_option("dorea_campaigninfo_user_" . wp_get_current_user()->user_login);
    }

    function is_paid($order, $campaignList)
    {
        static $purchaseCounts;
        $displayName = $order->billing->first_name . " " . $order->billing->last_name ;
        $userEmail = $order->billing->email;

        foreach ($campaignList as $campaigns) {

            foreach ($campaigns['campaignNames'] as $campaignName){

                if (isset($this->campaignInfo()[$campaignName])) {
                    $purchaseCounts = [$campaignName=>$this->campaignInfo()[$campaignName]['count'] + 1];
                } else {
                    $purchaseCounts = [$campaignName=>1];
                }

            }

            // it must trigger and count campaign on every each of product
            $items = ['displayName' => $displayName, 'userEmail' => $userEmail, 'purchaseCounts'=>$purchaseCounts];
            $campaignInfo = array_merge($campaigns, $items);

            $this->receiptModel->add($campaignInfo);

        }

    }
}
