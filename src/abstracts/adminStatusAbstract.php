<?php


namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for admin status class controller
 */
abstract class adminStatusAbstract
{

    function __construct()
    {

    }

    abstract function set(int $timestamp);
    abstract function paid();

}