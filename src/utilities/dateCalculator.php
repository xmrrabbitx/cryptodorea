<?php

namespace Cryptodorea\DoreaCashback\utilities;

use Cryptodorea\DoreaCashback\abstracts\utilities\dateCalculatorAbstract;

/**
 * calculate current time in the unix format
 * @param $date in Date Format
 */
class dateCalculator extends dateCalculatorAbstract
{
    public function currentDate()
    {
        return current_time('timestamp');
    }

    /**
     * calculate unix date in Month Format
     * @param $date in Date Format
     */
    public function unixToYear($time)
    {
        return gmdate('Y', $time);
    }

    /**
     * calculate unix date in Month Format
     * @param $date in Date Format
     */
    public function unixToMonth($time)
    {
        return gmdate('F', $time);
    }

    /**
     * calculate unix date in Day Format
     * @param $date in Date Format
     */
    public function unixToday($time)
    {
        return gmdate('d', $time);
    }
}