<?php

namespace Cryptodorea\Woocryptodorea\abstracts\utilities;

/**
 * an abstract interface for date calculator utilities
 */
abstract class dateCalculatorAbstract
{

    function __construct()
    {

    }

    abstract  function currentDate();
    abstract  function unixToMonth($time);
    abstract  function unixToday($time);
    abstract  function futureDate($month, $day, $year);

}