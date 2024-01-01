<?php

require(WP_PLUGIN_DIR . "/dorea/controllers/debugController.php");

function databaseError($error){
  
    $debug =  new debugController();
    $debug->databasError($error);

}


function databaseDebug($messg){
    $debug =  new debugController();
    $debug->databasError($messg);
}