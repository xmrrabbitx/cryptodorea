<?php


abstract class payAbstract{

    public function __construct(){

    }

    abstract function checkExpire($campaignName);
    abstract function pay();
}