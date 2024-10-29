<?php

namespace Cryptodorea\DoreaCashback\abstracts\model;

/**
 * an abstract class for receipt model
 */
abstract class receiptModelAbstract
{

    abstract function list();

    abstract function add($campaignInfo);

}