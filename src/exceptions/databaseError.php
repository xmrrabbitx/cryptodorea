<?php

namespace Cryptodorea\Woocryptodorea\exceptions;

use Cryptodorea\Woocryptodorea\controllers\debugController;

function databaseError($error){
  
    $debug =  new debugController();
    $debug->databasError($error);

}


function databaseDebug($messg){
    $debug =  new debugController();
    $debug->databasError($messg);
}