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
        static $resultInfo;
        static $total;

        $displayName = $order->billing->first_name . " " . $order->billing->last_name ;
        $userEmail = $order->billing->email;
        $campaignListKeys = array_keys($campaignList);

        foreach ($campaignListKeys as $campaignName) {

            if (!isset($campaignList[$campaignName]['order_ids'])) {

                    if (isset($campaignList[$campaignName]['purchaseCounts'])) {

                        $purchaseCounts =  $campaignList[$campaignName]['purchaseCounts'] + 1;

                    } else {

                        $purchaseCounts = 1;

                    }

                    // add sum of total to list
                    $campaignList[$campaignName]['total'][] =  $order->total;

            }

            $campaignList[$campaignName]['order_ids'][] = $order->id;

            // it must trigger and count campaign on every each of product
            $items = ['displayName' => $displayName, 'userEmail' => $userEmail, 'purchaseCounts' => $purchaseCounts];
            $campaignInfo = array_merge($campaignList[$campaignName], $items);

            $campaignList[$campaignName] = $campaignInfo;

        }

        // store campaign info into model
        $this->receiptModel->add($campaignList);
    }
}
