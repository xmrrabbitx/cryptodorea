<?php

namespace Cryptodorea\Woocryptodorea\abstracts\utilities;

/**
 * an abstract interface for encrypt utilities
 */
abstract class encryptAbstract
{

    function __construct()
    {

    }

    abstract  function encrypt($data);

}