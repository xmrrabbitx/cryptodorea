<?php

/*
Plugin Name: Dorea CashBack
Description: A New way of Crypto Cash Back to your most loyal customers
Version: 1.0.0
*/

// check security
defined( 'ABSPATH' ) || exit;
define( 'DoreaCashBack_VERSION', '1.0.0' );
define( 'DoreaCashBack_URI', plugin_dir_url( __FILE__ ) );



// include necessary files
include_once(__DIR__ . '/abstracts/doreaAbstract.php');
include_once(__DIR__ . '/model/doreaDB.php');
include_once(__DIR__ . '/config/conf.php');
include_once(__DIR__ . '/view/admin/admin.php');
include_once(__DIR__ . '/view/checkout/checkout.php');
include_once(__DIR__ . '/view/receipe/receipe.php');



/**
 * a Class for handling the Cash Back programm
 */
class doreaCashBack extends doreaAbstract{

    private $doreaDB;

    public function __construct(){

        // create instance database on initial load
        $this->doreaDB = new doreaDB();
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

        $logDirectory = WP_PLUGIN_DIR . "/dorea/debug/";
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
        
        //$time = '1708068853';//time();
        //$date = date('F j, Y, g:i a',$time);
        //echo mktime(0, 0, 0, 7, 1, 2000);
        //var_dump(delete_transient('dorea'));
        //var_dump(delete_option('campaigninfo_user'));
        //var_dump(delete_option('campaign_list'));
        var_dump(get_transient('dorea'));
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

        $self = $this;
        
        add_action('woocommerce_thankyou',function($order_id) use($self){
            $self->isPaid($order_id);
        });
        
    }

    /**
     * call back for place order
     * check if order paid or not!
     */
    public function isPaid($order_id){
 
        //print_r($order_id);

        // Check if the order has been paid
        if($order_id){
            $order = wc_get_order($order_id);
            //print_r($order);

            $user = $order->get_user();
            $userName = $user->user_login;
            $displayName = $user->display_name;
            $userEmail = $user->user_email; 
            //print_r($displayName);

            // store data into sqlite database
            /**
             * @param $order_id
             * @param $userName
             * @param $displayName
             * @param $userEmail
            */
            $this->doreaDB->init();

  
        }
        

    }

    
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    if (!class_exists('WooCommerce')) {
       
        include_once ABSPATH . 'wp-content/plugins/woocommerce/woocommerce.php'; 
    }
 
    $dorea = new doreaCashBack();
    $dorea->checkPlaceOrder();
    $dorea->test();

}

?>