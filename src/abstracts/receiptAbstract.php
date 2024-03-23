<?php

namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for receipt class
 */
abstract class receiptAbstract{

    function __construct(){

    }

    abstract function campaignInfo();

    abstract function is_paid($order, $campaignList);
    
}