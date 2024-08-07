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
        $campaignInfoUser = get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login);
        $campaignsList = get_option("campaign_list");

        if($queueDeleteCampaigns) {

            if($campaignInfoUser) {

                $i = 0;
                foreach ($queueDeleteCampaigns as $campaigns) {

                    foreach ($campaignInfoUser as $campaignUser) {

                        if (!empty($campaignUser['campaignNames'])) {

                            if (in_array($campaigns, $campaignUser['campaignNames'])) {

                                $key = array_search($campaigns, $campaignUser['campaignNames']);
                                unset($campaignUser["campaignNames"][$key]);
                                //unset($campaignUser["campaignNames"]);
                                //unset($campaignUser["walletAddress"]);
                                $campaignInfoUser[0] = $campaignUser;
                                var_dump("trigger");
                                //var_dump($campaignInfoUser);
                                //$campaignInfoUser[$i]['campaignNames'] = $campaignUser["campaignNames"];
                                //var_dump($campaignUser["campaignNames"]);
                                //var_dump($campaignUser);
                                update_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfoUser);
                                //unset($queueDeleteCampaigns[$i]);
                                //update_option("dorea_queue_delete_campaigns", $queueDeleteCampaigns);


                            }
                            //var_dump($campaignInfoUser);
                            if (empty($campaignInfoUser[$i]['campaignNames'])) {
                                unset($campaignInfoUser[$i]);
                                var_dump("its emptyyyyy");
                                delete_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login, $campaignInfoUser);
                            }

                        }
                    }
                    //die("stoppp");


                    $i += 1;
                }

                var_dump(get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login));
                //die("stoppp");

            }
            if (empty(get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login))) {
                delete_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login);
            }
        }
    }

}
