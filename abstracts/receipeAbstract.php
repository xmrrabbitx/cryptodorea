<?php


abstract class receipeAbstract{


    function __construct(){

    }

    abstract function is_paid($order);
}