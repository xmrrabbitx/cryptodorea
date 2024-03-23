<?php

namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for pay class
 */
abstract class payAbstract{

    public function __construct(){

    }

    abstract function checkExpire($campaignName);
    abstract function pay();
}