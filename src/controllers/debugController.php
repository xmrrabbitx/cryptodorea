<?php

namespace Cryptodorea\Woocryptodorea\controllers;
use Cryptodorea\Woocryptodorea\abstracts\debugAbstract;

/**
 * controller for debug errors
 */
class debugController extends debugAbstract
{

    private $path;

    function __construct()
    {

        $this->path = WP_PLUGIN_DIR . '/woo-cryptodorea/debug';

    }

    public function databasError($error)
    {

        $errorFile = fopen($this->path . '/databasError.log', 'a+');
        fwrite($errorFile,
            $error . " __ timestamps: (" . date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000)) . ") \n"
        );
        fclose($errorFile);

    }
}