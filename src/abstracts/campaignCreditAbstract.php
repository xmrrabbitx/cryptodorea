<?php

namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for campaign credit class controller
 */
abstract class campaignCreditAbstract
{
    abstract function encryptionGeneration($key, $value, $encryptionMessage);
    abstract function nextEncryption($key, $value, $encryptionMessage);

}