<?php

namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for checkout class controller
 */
abstract class checkoutAbstract{

    function __construct(){

    }

    abstract function check($cashbackList);
    abstract function campaignDiff($campaignNames);
    abstract function addtoList($campaignNames, $userWalletAddress);
    abstract function autoRemove();
    abstract function expire(string $campaign);

}