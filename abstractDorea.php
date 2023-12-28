<?php

/**
 * an Abstract Class for Crypto Dorea Plugin
 */
abstract class abstractDorea{

    public function __construct(){


    }

    abstract function addCashBackToCart();

    abstract function checkCashBackToCart();

    abstract function checkPlaceOrder();

}