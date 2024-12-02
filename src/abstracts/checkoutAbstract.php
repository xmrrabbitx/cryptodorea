<?php

namespace Cryptodorea\DoreaCashback\abstracts;

/**
 * an abstract interface for checkout class controller
 */
abstract class checkoutAbstract{

    abstract function check($cashbackList);
    abstract function campaignDiff($campaignNames);
    abstract function addtoList($campaignNames, $userWalletAddress);
    abstract function autoRemove();
    abstract function expire(string $campaign);
    abstract function orederReceived($order);

}