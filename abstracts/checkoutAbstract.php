<?php


/**
 * an abstract interface for checkout class controller
 */
abstract class checkoutAbstract{

    function __contruct(){

    }

    abstract function check($campaignNames);
    abstract function add($campaignNames);
    abstract function update($campaignNames);

}