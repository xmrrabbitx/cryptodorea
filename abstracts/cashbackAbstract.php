<?php

namespace cryptodorea\woocryptodorea\abstracts;

/**
 * an abstract interface for cashback class controller
 */
abstract class cashbackAbstract{

    function __contruct(){

    }

    abstract function create($campaignName, $cryptoType, $cryptoAmount,  $shoppingCount, $startDateMonth, $startDateDay, $expDateMonth, $expDateDay);

    abstract function list();

    abstract function modify();

    abstract function remove($campaignName);

}