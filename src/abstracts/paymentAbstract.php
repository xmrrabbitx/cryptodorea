<?php


namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for admin payment class controller
 */
abstract class paymentAbstract
{
    abstract function walletslist(string $campaignName);
}