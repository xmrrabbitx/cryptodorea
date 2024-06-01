<?php

/*
Plugin Name: Dorea CashBack
Description: A New way of Crypto Cash Back to your most loyal customers
Version: 1.0.0
*/


include_once __DIR__ . '/vendor/autoload.php';

include_once __DIR__ . '/src/loader.php';
use Cryptodorea\WooCryptodorea\loader;

$loader = new  loader();
$loader->testing();
