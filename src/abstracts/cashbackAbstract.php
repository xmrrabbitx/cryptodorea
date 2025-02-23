<?php

namespace Cryptodorea\DoreaCashback\abstracts;

/**
 * an abstract interface for cashback class controller
 */
abstract class cashbackAbstract{
    abstract function create(string $campaignName, string $campaignNameLable, string $cryptoType, int $cryptoAmount,  int $shoppingCount, string $campaignSlogan, int $timestampStart, int $timestampExpire);

    abstract function list();

    abstract function remove($campaignName);
}