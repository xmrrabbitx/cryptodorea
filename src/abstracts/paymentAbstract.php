<?php


namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for admin payment class controller
 */
abstract class paymentAbstract
{

    function __construct()
    {

    }

    abstract function list(string $campaignName);


}