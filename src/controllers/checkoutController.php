<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\checkoutAbstract;
use Cryptodorea\Woocryptodorea\model\checkoutModel;

/**
 * Controller for checkout
 */
class checkoutController extends checkoutAbstract
{

    function __construct()
    {

        $this->checkoutModel = new checkoutModel();

    }

    public function check($campaignNames)
    {

        if ($this->checkoutModel->list()) {

            $campaignList = $this->checkoutModel->list();
            foreach ($campaignNames as $campaign) {
                if (!in_array($campaign, $campaignList)) {
                    return true;
                }
            }

            return false;
        }
    }

    public function addtoList($campaignNames, $walletAddress)
    {

        $this->checkoutModel->add($campaignNames, $walletAddress);

    }

    public function checkout()
    {

        // get Json Data
        $json_data = file_get_contents('php://input');

        // issue is here
        if (!empty($json_data)) {
            $campaignLists = json_decode($json_data);

            try {

                $this->addtoList($campaignLists->campaignlist, $campaignLists->walletAddress);

                // throw new Exception('something went wrong!');
            } catch (Exception $error) {
                //
            }


        }


    }


    public function orederReceived()
    {
        var_dump(get_option('campaignlist_user'));
        var_dump(get_option('dorea_user_walletAddress'));
    }

}
