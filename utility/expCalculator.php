<?php


/**
 * calculate time untile campaign expires
 * @param $exp is in Day Format
 */

 function expCalculator($exp){

    return $exp * 24 * 60 * 60;

 }