<?php

namespace Cryptodorea\DoreaCashback\controllers;

use Cryptodorea\DoreaCashback\abstracts\checkoutAbstract;
use Cryptodorea\DoreaCashback\model\checkoutModel;
use Cryptodorea\DoreaCashback\controllers\receiptController;
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

    public function checkout($campaignLists, $walletAddress)
    {

        $this->addtoList($campaignLists, $walletAddress);
        $this->addtoListUsers($campaignLists);

    }

    public function orederReceived($order, $order_obj):void
    {

       // call receipt controller
       $receipt = new receiptController();
       $receipt->is_paid($order,$order_obj,  $this->checkoutModel->list());

    }

    public function autoRemove():void
    {

        $queueDeleteCampaigns = get_transient('dorea_queue_delete_campaigns');
        $campaignInfoUser = $this->checkoutModel->list();
        $campaignInfoUserKeys = array_keys($campaignInfoUser);
        $campaignList = get_option("dorea_campaign_list");

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
        }
        if($campaignList){
            foreach ($campaignInfoUserKeys as $campaigns) {
                if (!in_array($campaigns, $campaignList)) {
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

    // check if expired campaign!
    public function expire($campaign):bool
    {
        $camapaignInfo = get_transient('dorea_' . $campaign);

        $currentDate = current_time('timestamp');

        return $camapaignInfo['timestampExpire'] >= $currentDate && $camapaignInfo['timestampStart'] <= $currentDate ?? false;

    }

    // check if expired or not started campaign!
    public function checkTimestamp($campaign):string
    {
        $camapaignInfo = get_transient('dorea_' . $campaign);

        $currentDate = current_time('timestamp');

        if($camapaignInfo['timestampExpire'] < $currentDate){
            return "expired";
        }elseif($camapaignInfo['timestampStart'] > $currentDate){
            return "notStarted";
        }

        return "";

    }

    public function timestampToDate($campaign)
    {
        $camapaignInfo = get_transient('dorea_' . $campaign);

        return gmdate('Y-m-d H:i:s', $camapaignInfo['timestampStart']);
    }

    /**
     * get cart items categories
     */
    function doreaCartCategories(string $campaign):array
    {
        $productCategoriesUser = get_option('dorea_category_products_' . $campaign) ?? null;
        $productCategories = [];
        // check product categories
        if(!empty($productCategoriesUser)){
            foreach (WC()->cart->get_cart() as $cart_item) {
                $product = $cart_item['data'];
                $product_id = $product->get_id();
                $categories = strip_tags(wc_get_product_category_list($product_id));

                foreach ($productCategoriesUser as $cat) {
                    if (str_contains($categories, $cat)) {
                        $productCategories[] = true;
                    } else {
                        $productCategories[] = false;
                    }
                }
            }
        }

        return $productCategories;
    }
}
