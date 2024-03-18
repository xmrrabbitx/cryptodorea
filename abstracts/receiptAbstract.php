<?php


abstract class receiptAbstract{


    function __construct(){

    }

    abstract function campaignInfo();

    abstract function is_paid($order, $campaignList);
    
}