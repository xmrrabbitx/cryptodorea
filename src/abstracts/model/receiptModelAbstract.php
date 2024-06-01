<?php

namespace Cryptodorea\Woocryptodorea\abstracts\model;

/**
 * an abstract class for receipt model
 */
abstract class receiptModelAbstract
{

    function __construct()
    {

    }

    abstract function list();

    abstract function add($campaignInfo);

}