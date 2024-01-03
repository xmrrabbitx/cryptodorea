<?php


/**
 * an abstract interface 
 */
abstract class abstractDoreaDb{

    public function __construct(){


    }

    abstract function addtoCashBack($addToChProgram, $loyaltyName, $exp);

}