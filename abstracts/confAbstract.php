<?php

namespace cryptodorea\woocryptodorea\abstracts;

/**
 * an abstract interface for config class
 */
abstract class confAbstract{

    function __contruct(){

    }

    abstract function check($key);

    abstract function add($key, $arr):bool;

    abstract function remove($key):bool;
    
}