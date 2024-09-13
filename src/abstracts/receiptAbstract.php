<?php

namespace Cryptodorea\Woocryptodorea\abstracts;

/**
 * an abstract interface for receipt class
 */
abstract class receiptAbstract{
    abstract function is_paid($order, $campaignList);
}