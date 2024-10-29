<?php


namespace Cryptodorea\DoreaCashback\abstracts;

/**
 * an abstract interface for expire campaign class controller
 */
abstract class expireCampaignAbstract
{
    abstract function check(int $timestamp);
}