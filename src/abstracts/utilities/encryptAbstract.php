<?php

namespace Cryptodorea\DoreaCashback\abstracts\utilities;

/**
 * an abstract interface for encrypt utilities
 */
abstract class encryptAbstract
{

    function __construct()
    {

    }

    abstract  function randomSha256();
    abstract  function sha256Salt();
    abstract  function encryptAes($data);
    abstract  function decryptAes($data, $key, $iv);
    abstract function encryptGenerate(string $campaignName);
    abstract function keccak($key, $value);

}