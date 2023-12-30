<?php

require(WP_PLUGIN_DIR . "/dorea/controllers/debugController.php");

function databaseError($error){
  
    $debug =  new debugController();
    $debug->error($error);

}