<?php

namespace Cryptodorea\DoreaCashback\abstracts;

/**
 * an abstract interface for config class
 */
abstract class confAbstract{
    abstract function check($key);

    abstract function add($key, $arr):bool;

    abstract function remove($key):bool;
}