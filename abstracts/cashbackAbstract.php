<?php


/**
 * an abstract interface for cashback class controller
 */
abstract class cashbackAbstract{

    function __contruct(){

    }

    abstract function create($campaignName, $cryptoType, $cryptoAmount,  $shoppingCount, $startDate, $expDate);

    abstract function list();

    abstract function modify();

    abstract function remove($campaignName);

}