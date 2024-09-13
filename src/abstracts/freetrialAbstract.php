<?php

namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for freetrial class controller
 */
abstract class freetrialAbstract
{
    abstract  function set();
    abstract  function expire();

    abstract function remainedDays();

}