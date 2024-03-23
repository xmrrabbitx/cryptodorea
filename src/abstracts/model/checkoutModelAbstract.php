<?php

namespace Cryptodorea\Woocryptodorea\abstracts\model;
abstract class checkoutModelAbstract
{

    function __contruct()
    {

    }

    abstract function list();

    abstract function add($campaignNames);

}