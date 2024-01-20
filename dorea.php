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



/**
 * a Class for handling the Cash Back programm
 */
class doreaCashBack extends doreaAbstract{

    private $doreaDB;

    public function __construct(){

        // create instance database on initial load
        $this->doreaDB = new doreaDB();
        $this->conf = new config();

    }

    /**
     * add Cash Back program to Cart page
     */
    public function addCashBackToCart(){
      
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
        session_start();
       
       // $ch = $this->conf->add(['hadi','mirzaie']);
      //$this->conf->remove('init_config');
        $ch = $this->conf->check('init_config');
       print_r($ch);
    }

    /**
     * remove Cash Back option from the Cart page
     */


    /**
     * cart page session actions
     */
    public function addtoCashBack(){
        //woocommerce_thankyou
        $self = $this;
        add_action('wp',function() use($self){
            $self->checkaddtoCashBack();
        });
    
    }

    /**
     * callback function to check session cart page
     */
    public function checkaddtoCashBack(){


    }

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
    $dorea->addCashBackToCart();
    $dorea->addtoCashBack();
    $dorea->checkPlaceOrder();
    $dorea->test();

}

?>