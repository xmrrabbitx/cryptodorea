<?php

namespace Cryptodorea\DoreaCashback\abstracts;

/**
 * an abstract interface for cashback class controller
 */
abstract class cashbackAbstract{
    abstract function create(string $campaignName, string $campaignNameLable, string $cryptoType, int $cryptoAmount,  int $shoppingCount, int $timestampStart, int $timestampExpire);

    abstract function list();

    abstract function modify();

    abstract function remove($campaignName);
}