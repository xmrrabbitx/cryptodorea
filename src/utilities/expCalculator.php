<?php

namespace Cryptodorea\Woocryptodorea\utilities;

use Cryptodorea\Woocryptodorea\abstracts\utilities\expCalculatorAbstract;

/**
 * calculate time untile campaign expires
 * @param $exp is in Day Format
 */
class expCalculator extends expCalculatorAbstract
{
    function expTime(int $exp)
    {

        return $exp * 24 * 60 * 60;

    }
}