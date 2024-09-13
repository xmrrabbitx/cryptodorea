<?php


namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for users class controller
 */
abstract class usersAbstract
{
    abstract function is_paid(string $campaignName, array $usersList, array $amount, array $totalPurchases);
}