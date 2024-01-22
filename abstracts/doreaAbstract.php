<?php

/**
 * an Abstract Class for Crypto Dorea Plugin
 */
abstract class doreaAbstract{

    public function __construct(){


    }

    abstract function checkPlaceOrder();
    abstract function isPaid($order_id);

}