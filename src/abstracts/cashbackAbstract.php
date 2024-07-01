<?php

namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for cashback class controller
 */
abstract class cashbackAbstract{

    function __contruct(){

    }

    abstract function create($campaignName, $cryptoType, $cryptoAmount,  $shoppingCount, $startDateYear, $startDateMonth, $startDateDay, $expDateMonth, $expDateDay, $timestampDate);

    abstract function list();

    abstract function modify();

    abstract function remove($campaignName);

}