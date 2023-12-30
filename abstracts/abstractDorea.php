<?php

/**
 * an Abstract Class for Crypto Dorea Plugin
 */
abstract class abstractDorea{

    public function __construct(){


    }

    abstract function addCashBackToCart();

    abstract function checkCbToCart();
    abstract function checkCbToCartState();

    abstract function checkPlaceOrder();
    abstract function isPaid($order_id);

}