<?php

namespace Cryptodorea\DoreaCashback\abstracts\utilities;

/**
 * an interface for compile class
 */
abstract class compileAbstract
{
    function __construct()
    {

    }

    abstract function abi();

    abstract function bytecode();
}