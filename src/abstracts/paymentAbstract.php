<?php


namespace Cryptodorea\DoreaCashback\abstracts;

/**
 * an abstract interface for admin payment class controller
 */
abstract class paymentAbstract
{
    abstract function walletslist(string $campaignName);
}