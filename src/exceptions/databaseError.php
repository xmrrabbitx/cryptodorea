<?php

namespace Cryptodorea\DoreaCashback\exceptions;

use Cryptodorea\DoreaCashback\controllers\debugController;

function databaseError($error){
  
    $debug =  new debugController();
    $debug->databasError($error);

}


function databaseDebug($messg){
    $debug =  new debugController();
    $debug->databasError($messg);
}