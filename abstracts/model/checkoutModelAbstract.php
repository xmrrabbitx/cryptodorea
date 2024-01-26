<?php

abstract class checkoutModelAbstract{

    function __contruct(){

    }

    abstract function list();

    abstract function add($campaignNames);

    abstract function update($campaignNames);

}