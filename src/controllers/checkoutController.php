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

        if ($this->checkoutModel->list() && !empty($this->checkoutModel->list())){

            $campaignList = $this->checkoutModel->list();
            foreach ($campaignNames as $campaign) {
                if (!in_array($campaign, $campaignList)) {
                    return true;
                }
            }
            return false;
        }else{
            return true;
        }

    }

    public function addtoList($campaignNames)
    {

        $this->checkoutModel->add($campaignNames);

    }

    public function checkout()
    {

        // get Json Data
        $json_data = file_get_contents('php://input');

        // issue is here
        if (!empty($json_data)) {
            $campaignLists = json_decode($json_data);

            try {

                $this->addtoList($campaignLists->campaignlist);

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

}
