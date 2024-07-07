<?php

namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for autoremove class controller
 */
abstract class autoremoveAbstract
{

    function __construct()
    {

    }

    abstract  function remove($campaignName);

}