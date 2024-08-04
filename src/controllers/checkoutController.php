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

    public function check($campaignNames)
    {

        if ($this->checkoutModel->list() && !empty($this->checkoutModel->list())) {

            $campaignList = $this->checkoutModel->list();

            $campaignNamesList = [];
            foreach ($campaignList as $campaign) {

                $campaignNamesList = array_merge($campaignNamesList,$campaign['campaignNames']);

            }
            if(!empty($campaignNamesList)) {
                $diff = array_diff($campaignNames, $campaignNamesList);
                if (!empty($diff)) {
                    return true;
                }else{
                    return false;
                }
            }

        }
    }

    public function campaignDiff($campaignNames)
    {

        if ($this->checkoutModel->list() && !empty($this->checkoutModel->list())) {

            $campaignList = $this->checkoutModel->list();

            $campaignNamesList = [];
            foreach ($campaignList as $campaign) {

                $campaignNamesList = array_merge($campaignNamesList,$campaign['campaignNames']);

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

    public function addtoListUsers()
    {

        $this->checkoutModel->addUser();

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
                $this->addtoListUsers();

                // throw new Exception('something went wrong!');
            } catch (Exception $error) {
                //
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

        $queueDeleteCampaigns = get_option('dorea_queue_delete_campaigns');

        if($queueDeleteCampaigns) {
            // remove user DB records
            $campaignInfoUser = get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login);

            $i = 0;
            foreach ($queueDeleteCampaigns as $campaigns) {

                foreach ($campaignInfoUser as $campaignUser) {
                    if($campaignUser['campaignNames']) {
                        if (in_array($campaigns, $campaignUser['campaignNames'])) {

                            $key = array_search($campaigns, $campaignUser['campaignNames']);
                            unset($campaignUser["campaignNames"][$key]);
                            $campaignInfoUser[$i]['campaignNames'] = $campaignUser["campaignNames"];
                            update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfoUser);
                            unset($queueDeleteCampaigns[$i]);
                            update_option("dorea_queue_delete_campaigns", $queueDeleteCampaigns);

                        }
                    }
                }
                if (empty($campaignInfoUser[$i]['campaignNames'])) {
                    unset($campaignInfoUser[$i]);
                    update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfoUser);
                }
                $i += 1;
            }

            if (empty(get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login))) {
                delete_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login);
            }
        }
    }

}
