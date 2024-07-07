<?php

namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for config class
 */
abstract class confAbstract{

    function __construct(){

    }

    abstract function check($key);

    abstract function add($key, $arr):bool;

    abstract function remove($key):bool;
    
}