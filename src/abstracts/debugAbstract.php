<?php

namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for debug controller
*/
abstract class debugAbstract{

    function __construct(){
        
    }

    abstract function databasError($error);
}