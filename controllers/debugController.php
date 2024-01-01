<?php

require(WP_PLUGIN_DIR . "/dorea/abstracts/debugAbstract.php");

class debugController extends debugAbstract{

    private $path;
    function __construct(){

        $this->path = WP_PLUGIN_DIR . '/dorea/debug';

    }

    public function databasError($error){

        $errorFile = fopen($this->path . '/databasError.log','a+');
        fwrite($errorFile,
            $error . " __ timestamps: (". date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000)) . ") \n"
        );
        fclose($errorFile);
        
    }
}