<?php

namespace Cryptodorea\DoreaCashback\controllers;

use Cryptodorea\DoreaCashback\model\receiptModel;
use Cryptodorea\DoreaCashback\abstracts\receiptAbstract;

/**
 * a class for receipt controller
 */
class receiptController extends receiptAbstract
{

    function __construct()
    {

        $this->receiptModel = new receiptModel();

    }

    function is_paid($order, $campaignList):void
    {

        static $campaignInfoResult;

        $displayName = $order->billing->first_name . " " . $order->billing->last_name ;
        $userEmail = sanitize_email($order->billing->email);
        $campaignListKeys = array_keys($campaignList);

        $checkoutController = new checkoutController;
        // update old campaigns && check expiration
        $campaignListKeys = array_filter($campaignListKeys, function ($campaignName) use ($checkoutController) {
            return $checkoutController->expire($campaignName);
        }, ARRAY_FILTER_USE_BOTH);

        // check if any campaign needs to update
        if(!empty($campaignListKeys)) {
            foreach ($campaignListKeys as $campaignName) {

                $orderIds = $campaignList[$campaignName]['order_ids'] ?? [];

                if (!in_array($order->id, $orderIds)) {

                    if (isset($campaignList[$campaignName]['purchaseCounts'])) {

                        $purchaseCounts = $campaignList[$campaignName]['purchaseCounts'] + 1;

                    } else {

                        $purchaseCounts = 1;

                    }

                    // add sum of total to list
                    $campaignList[$campaignName]['total'][] = (float)$order->total;

                    $campaignList[$campaignName]['order_ids'][] = $order->id;

                    // it must trigger and count campaign on every each of product
                    $items = ['displayName' => $displayName, 'userEmail' => $userEmail, 'purchaseCounts' => $purchaseCounts];
                    $campaignInfo = array_merge($campaignList[$campaignName], $items);

                    $campaignList[$campaignName] = $campaignInfo;
                    $campaignInfoResult = $campaignList;

                }

            }

            if ($campaignInfoResult) {
                // store campaign info into model
                $this->receiptModel->add($campaignInfoResult);
            }
        }
    }
}
