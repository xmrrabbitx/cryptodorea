<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\checkoutAbstract;
use Cryptodorea\Woocryptodorea\model\checkoutModel;
use Cryptodorea\Woocryptodorea\controllers\receiptController;
use Cryptodorea\Woocryptodorea\utilities\Encrypt;
use WC_Order;

/**
 * Controller for checkout
 */
class checkoutController extends checkoutAbstract
{

    function __construct()
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

    public function checkout()
    {

        // get Json Data
        $data = file_get_contents('php://input');
        $json = json_decode($data);

        // issue is here
        if (!empty($json)) {

            $campaignLists = $json->campaignlists;
            $userWalletAddress = $json->walletAddress;

            try {

                $this->addtoList($campaignLists, $userWalletAddress);
                $this->addtoListUsers($campaignLists);


            } catch (Exception $error) {
                //throw exception
            }


        }


    }

    public function orederReceived($orderId)
    {
        global $woocommerce, $post;

        $order = json_decode(new WC_Order($orderId));

        if(isset($order->id)){

            // call receipt controller
            $receipt = new receiptController();
            $receipt->is_paid($order, $this->list());

        }
    }

    public function remove()
    {

        $queueDeleteCampaigns = get_transient('dorea_queue_delete_campaigns');
        $campaignInfoUser = $this->list();
        $campaignInfoUserKeys = array_keys($campaignInfoUser);
        $campaignsList = get_option('campaign_list');

        if($queueDeleteCampaigns) {

            if($campaignInfoUser) {

                foreach ($queueDeleteCampaigns as $campaigns) {

                    //foreach ($campaignInfoUser as $campaignUser) {

                        //if (!empty($campaignUser['campaignNames'])) {

                            // remove campaign in delete queue
                            if (in_array($campaigns, $campaignInfoUserKeys)) {
                                //$key = array_search($campaigns, $campaignUser['campaignNames']);
                                unset($campaignInfoUser[$campaigns]);

                                //$campaignInfoUser[0] = $campaignUser;
                                //update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfoUser);
                            }

                        //}
                        if (empty($campaignUser[$campaigns])){
                            delete_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfoUser);
                        }
                   // }
                }

            }
            if (empty($campaignInfoUser)) {
                delete_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login);
            }
        }
/*
        if($campaignInfoUser) {
            foreach ($campaignInfoUser as $campaigns) {
                foreach ($campaigns['campaignNames'] as $campaignNames) {

                    // remove redundant old campaigns
                    if (!in_array($campaignNames, $campaignsList)) {


                        $keyCamp = array_search($campaignNames, $campaigns['campaignNames']);

                        unset($campaigns["campaignNames"][$keyCamp]);
                        unset($campaigns["total"][$campaignNames]);
                        unset($campaigns["purchaseCounts"][$campaignNames]);

                        update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaigns);

                    }

                    if (empty($campaignUser['campaignNames'])) {

                        delete_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login);
                    }
                }
            }
        }
 */
    }

}
