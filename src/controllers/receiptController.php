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

        $displayName = $order->billing->first_name . " " . $order->billing->last_name ;
        $userEmail = $order->billing->email;

        foreach ($campaignList as $campaigns) {

            if (!isset($campaigns['order_ids']) || !in_array($order->id, $campaigns['order_ids'])) {
                foreach ($campaigns['campaignNames'] as $campaignName) {

                    if (isset($campaigns['purchaseCounts'][$campaignName])) {

                        // issue here
                        $purchaseCounts[$campaignName] =  $campaigns['purchaseCounts'][$campaignName] + 1;

                    } else {
                        $purchaseCounts[$campaignName] = 1;

                    }

                }

                $campaigns['order_ids'][] = $order->id;

                // it must trigger and count campaign on every each of product
                $items = ['displayName' => $displayName, 'userEmail' => $userEmail, 'purchaseCounts' => $purchaseCounts];
                $campaignInfo = array_merge($campaigns, $items);

                $resultInfo[] = $campaignInfo;


            }
        }
        if($resultInfo) {
            // store campaign info into model
            $this->receiptModel->add($resultInfo);
        }

    }
}
