<?php


namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for users class controller
 */
abstract class usersAbstract
{

    abstract function paid(string $campaignName, array $usersList, array $amount);

}