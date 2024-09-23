<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\checkoutAbstract;
use Cryptodorea\Woocryptodorea\model\checkoutModel;
use Cryptodorea\Woocryptodorea\controllers\receiptController;
use WC_Order;

/**
 * Controller for checkout
 */
class checkoutController extends checkoutAbstract
{
    public function __construct()
    {

        $this->checkoutModel = new checkoutModel();

    }

    public function list()
    {
        return $this->checkoutModel->list();
    }

    public function check($cashbackList): array
    {

        $cashbackList = $cashbackList !== false ? $cashbackList : [];

        $campaignListUser = $this->checkoutModel->list();
        $campaignListUserKeys = array_keys($campaignListUser);

        return array_diff($cashbackList, $campaignListUserKeys) ?? [];

    }

    public function campaignDiff($campaignNames)
    {

        if ($this->checkoutModel->list() && !empty($this->checkoutModel->list())) {

            $campaignList = $this->checkoutModel->list();

            $campaignNamesList = [];
            foreach ($campaignList as $campaign) {

                $campaignNamesList = array_merge($campaignNamesList,$campaign[$campaign]);

            }
            if(!empty($campaignNamesList)) {
                $diff = array_diff($campaignNames, $campaignNamesList);
                if (!empty($diff)) {
                    return $diff;
                }
            }

        }
    }

    public function addtoList($campaignNames, $userWalletAddress)
    {

        $this->checkoutModel->add($campaignNames,$userWalletAddress);

    }

    public function addtoListUsers($campaignLists)
    {

        $this->checkoutModel->addUser($campaignLists);

    }

    public function checkout($json)
    {

        $campaignLists = (array)$json->campaignlists;
        $userWalletAddress = htmlspecialchars($json->walletAddress);

        $this->addtoList($campaignLists, $userWalletAddress);
        $this->addtoListUsers($campaignLists);

    }

    public function orederReceived($order,$orderId):void
    {

       // call receipt controller
       $receipt = new receiptController();
       $receipt->is_paid($order, $this->checkoutModel->list());

    }

    public function autoRemove():void
    {

        $queueDeleteCampaigns = get_transient('dorea_queue_delete_campaigns');
        $campaignInfoUser = $this->checkoutModel->list();
        $campaignInfoUserKeys = array_keys($campaignInfoUser);

        if($queueDeleteCampaigns) {

            if($campaignInfoUser) {

                foreach ($queueDeleteCampaigns as $campaigns) {

                    // remove campaign in delete queue
                    if (in_array($campaigns, $campaignInfoUserKeys)) {

                        unset($campaignInfoUser[$campaigns]);
                        update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfoUser);

                    }
                }
            }

            // remove empty campaign record
            if (empty($campaignInfoUser)) {
                delete_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login);
            }
        }
    }

    public function expire($campaign):bool
    {
        $camapaignInfo = get_transient($campaign);

        $currentDate = (int)strtotime(date("d.m.Y") . " 00:00:00");

        return $camapaignInfo['timestampExpire'] >= $currentDate && $camapaignInfo['timestampStart'] <= $currentDate ?? false;

    }
}
