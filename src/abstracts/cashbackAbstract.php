<?php

namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for cashback class controller
 */
abstract class cashbackAbstract{

    function __construct(){

    }

    abstract function create(string $campaignName, string $cryptoType, int $cryptoAmount,  int $shoppingCount, int $timestampStart, int $timestampExpire);

    abstract function list();

    abstract function modify();

    abstract function remove($campaignName);

}