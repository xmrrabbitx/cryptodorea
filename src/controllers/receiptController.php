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

    function is_paid($order, $order_obj, $campaignList):void
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

               $campaignCategories = get_option('doreaCategoryProducts' . $campaignName) ?? [];
               if(!empty($campaignCategories)) {
                    $total = [];
                    /**
                     * filter product categories
                     * */
                    foreach ($order_obj->get_items() as $item_id => $item) {
                        $price = $item->get_total();

                        $product = $item->get_product();
                        $product_id = $product->get_id();

                        // Get the categories for this product
                        $categories = get_the_terms($product_id, 'product_cat');

                        // Check if categories exist
                        if ($categories && !is_wp_error($categories)) {
                            $category_names = [];
                            foreach ($categories as $category) {
                                $category_names[] = $category->name;
                            }
                            $categories = implode(', ', $category_names);
                        }

                        if(in_array($categories, $campaignCategories)) {
                            $price = trim(strip_tags(html_entity_decode(wc_price($price))));
                            $total[] = (float)str_replace("$", '', $price);
                        }
                    }
                    $total = array_sum($total);
                }
               else{
                    $total = (float)$order->total;
                }

               $mode = get_transient('dorea_' . $campaignName)['mode'];
               if ($mode === "on") {
                        $orderIds = $campaignList[$campaignName]['order_ids'] ?? [];

                        if (!in_array($order->id, $orderIds)) {

                            if (isset($campaignList[$campaignName]['purchaseCounts'])) {

                                $purchaseCounts = $campaignList[$campaignName]['purchaseCounts'] + 1;

                            } else {

                                $purchaseCounts = 1;

                            }

                            // add sum of total to list
                            $campaignList[$campaignName]['total'][] = $total;

                            $campaignList[$campaignName]['order_ids'][] = $order->id;

                            // it must trigger and count campaign on every each of product
                            $items = ['displayName' => $displayName, 'userEmail' => $userEmail, 'purchaseCounts' => $purchaseCounts];
                            $campaignInfo = array_merge($campaignList[$campaignName], $items);

                            $campaignList[$campaignName] = $campaignInfo;
                            $campaignInfoResult = $campaignList;

                        }
                    }

            }

            if ($campaignInfoResult) {
                // store campaign info into model
                $this->receiptModel->add($campaignInfoResult);
            }
        }
    }
}
