<?php

namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for cashback class controller
 */
abstract class cashbackAbstract{

    function __construct(){

    }

    abstract function create($campaignName, $cryptoType, $cryptoAmount,  $shoppingCount, $startDateYear, $startDateMonth, $startDateDay, $expDateMonth, $expDateDay, $timestamp);

    abstract function list();

    abstract function modify();

    abstract function remove($campaignName);

}