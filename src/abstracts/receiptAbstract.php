<?php

namespace Cryptodorea\DoreaCashback\abstracts;

/**
 * an abstract interface for receipt class
 */
abstract class receiptAbstract{
    abstract function is_paid($order, $campaignList);
}