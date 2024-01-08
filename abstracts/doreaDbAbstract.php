<?php

/**
 * an abstract interface 
 */
abstract class doreaDbAbstract{

    public function __construct(){


    }

    abstract function addtoCashBack($addToChProgram, $loyaltyName, $exp);

}