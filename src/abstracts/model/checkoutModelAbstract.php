<?php

namespace Cryptodorea\DoreaCashback\abstracts\model;

/**
 * an abstract class for checkout model
 */
abstract class checkoutModelAbstract
{

    function __contruct()
    {

    }

    abstract function list();

    abstract function add($campaignNames, $userWalletAddress);

}