<?php

namespace Cryptodorea\DoreaCashback\abstracts;

/**
 * an abstract interface for campaign credit class controller
 */
abstract class campaignCreditAbstract
{
    abstract function encryptionGeneration($campaignName, $key, $value, $encryptionMessage);

}