<?php


/**
 * an abstract interface 
 */
abstract class abstractDoreaDb{

    public function __construct(){


    }

    abstract function createTable();

    abstract function close();

}