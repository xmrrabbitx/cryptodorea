<?php

namespace cryptodorea\woocryptodorea\abstracts;

/**
 * an abstract interface for pay class
 */
abstract class payAbstract{

    public function __construct(){

    }

    abstract function checkExpire($campaignName);
    abstract function pay();
}