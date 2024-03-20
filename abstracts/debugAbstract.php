<?php

namespace cryptodorea\woocryptodorea\abstracts;

/**
 * an abstract interface for debug controller
*/
abstract class debugAbstract{

    function __contruct(){
        
    }

    abstract function databasError($error);
}