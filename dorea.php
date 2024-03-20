<?php

/*
Plugin Name: Dorea CashBack
Description: A New way of Crypto Cash Back to your most loyal customers
Version: 1.0.0
*/

namespace cryptodorea\woocryptodorea;

define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );

// load vendor packages
require __DIR__ . '/vendor/autoload.php';

// load all files for once
include_once __DIR__ . '/abstracts/doreaAbstract.php';
//include_once __DIR__ . '/config/config.php';

// load all classes
use cryptodorea\woocryptodorea\abstracts\doreaAbstract;
use cryptodorea\woocryptodorea\config\Config;


/**
 * a Class for handling the Cash Back program
 */
class doreaCashBack extends doreaAbstract{

    private $doreaDB;

    public function __construct(){

        // create instance database on initial load
        $this->conf = new config();

        $this->removeCacheLogs();

        // set the session max lifetime to 2 hours
        $inactive = 7200;
        ini_set('session.gc_maxlifetime', $inactive);

        session_start();

        if (isset($_SESSION['campaignList_user']) && (time() - $_SESSION['time'] > $inactive)) {

            session_unset();
            session_destroy();
        }

    }

    /**
     * check and remove the size of log files in /debug directory
     */
    private function removeCacheLogs(){

        $logDirectory = WP_PLUGIN_DIR . "/woo-cryptodorea/debug/";
        $logFiles = glob($logDirectory . "*.log");
        $maxFileSize = 5 * 1024 * 1024;

        foreach ($logFiles as $logFile) {

            if(filesize($logFile) > $maxFileSize){

                unlink($logFile);

            }

        }

    }

    /**
     * test
     */
    public function test(){
        $self = $this;
        add_action('wp',function() use($self){
            $self->testing();
        });

    }

    public function testing(){
        //var_dump(delete_option('campaignlist_user'));
        //var_dump(get_option('dorea_queue_pay'));
        //var_dump(delete_option('dorea_queue_pay'));
        //$time = '1708068853';//time();
        //$date = date('F j, Y, g:i a',$time);
        //echo mktime(0, 0, 0, 7, 1, 2000);
        //var_dump(delete_transient('dorea'));
        //var_dump(delete_transient('dorea 1'));
        //var_dump(delete_transient('dorea 2'));
        //var_dump(delete_transient('dorea 3'));
        //var_dump(delete_transient('dorea 4'));
        //var_dump(delete_transient('dorea 5'));
        //var_dump(delete_option('campaigninfo_user'));
        //var_dump(delete_option('campaign_list'));
        //var_dump(get_transient('dorea 1'));
        //var_dump(get_transient('dorea 2'));
        //var_dump(get_transient('dorea 3'));
        //var_dump(get_option('campaigninfo_user'));
        //add_option('test_option',["name"=>"hadi"]);
        //var_dump(get_option('test_option')['name']);

    }

    /**
     * remove Cash Back option from the Cart page
     */


    /**
     * place order actions
     */
    public function checkPlaceOrder(){

        $queuePay = get_option('dorea_queue_pay');
        if($queuePay){
            $self = $this;
            add_action('wp',function() use($self){
                $self->timeToPay();
            });
        }
    }

    /**
     * call back for place order
     * check if order paid or not!
     */
    public function timeToPay(){

        // show payment modal to users
        paymentModal();

        $pay = new Pay();
        $paymentStatus = $pay->pay();
        if($paymentStatus){
            var_dump("time to delete all campaign info");
        }

    }


}



// check for woocoommerce plugin exists
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    if (!class_exists('WooCommerce')) {

        include_once ABSPATH . 'wp-content/plugins/woocommerce/woocommerce.php';
    }

    //$dorea = new doreaCashBack();
    //$dorea->checkPlaceOrder();
    //$dorea->test();

}


