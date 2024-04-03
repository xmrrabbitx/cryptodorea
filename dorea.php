<?php

/*
Plugin Name: Dorea CashBack
Description: A New way of Crypto Cash Back to your most loyal customers
Version: 1.0.0
*/


include_once __DIR__ . '/vendor/autoload.php';

include_once __DIR__ . '/src/loader.php';


//$loader = new  \Cryptodorea\Woocryptodorea\loader();
//$loader->testing();

function my_plugin_enqueue_scripts(): void
{

    wp_enqueue_script('my-script', plugin_dir_url(__FILE__) . '/src/js/dist/bundle.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'my_plugin_enqueue_scripts');

add_action("wp","test");

function test()
{
    print ('<script
  type="text/javascript"
  src="https://binaries.soliditylang.org/bin/{{ SOLC VERSION }}.js"
></script>');
    print ('
    
    ');
}