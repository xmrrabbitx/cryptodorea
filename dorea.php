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
include_once(__DIR__ . '/admin/admin.php');
include_once(__DIR__ . '/config/conf.php');


/**
 * a Class for handling the Cash Back programm
 */
class DoreaCashBack extends doreaAbstract{

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
       
        add_action('woocommerce_blocks_cart_enqueue_data','cashback',10,3);
        function cashback(){ 


            // add cash back program element to theme 
            print("<div style='margin-bottom:10px;padding:5px;padding-left:5px;'>
                    <p>
                        <h4>
                            add to cash back program 
                            <span>
                                <form method='POST'>
                                    <input id='add_to_cashback_checkbox_checked' type='checkbox' value='checked' onclick='add_to_cashback_checkbox()'>
                                </form>
                            </span>
                        </h4>
                    </p>
            </div>");

            // check and add to cash back program
            print("<script>
                        function add_to_cashback_checkbox() {
                            let add_to_cashback_checkbox_checked = document.getElementById('add_to_cashback_checkbox_checked');
                            if(add_to_cashback_checkbox_checked.checked && add_to_cashback_checkbox_checked.value === 'checked'){
                                
                                let xhr = new XMLHttpRequest();
                                xhr.open('POST', window.location.href, true);
                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                xhr.onreadystatechange = function() {
                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                        console.log(xhr.responseText);
                                    }
                                };
                                
                                xhr.send('cartSession=true');
                        
                                // Prevent the form from submitting (optional)
                                return false;
                            }
                        }
            </script>");
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

        $self = $this;
        add_action('wp',function() use($self){
            $self->checkaddtoCashBack();
        });
    
    }

    /**
     * callback function to check session cart page
     */
    public function checkaddtoCashBack(){

        dorea_add_menu_page();
        
        session_start();

        if(isset($_POST['cartSession'])){
            $session = $_POST['cartSession'] == "true";
            if($session && $session === true){    
               $_SESSION['cartSession'] = $session;
                $addToChProgram = $session;
                $loyaltyName = "jashnvareh2";
                $exp = 7 * 24 * 60 * 60;
                $this->doreaDB->addtoCashBack($addToChProgram, $loyaltyName, $exp);
            }
            
        }
       
        //print($_SESSION['cartSession']);
        //print(get_transient('jashnvareh2'));
        //unset($_SESSION['cartSession']);

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
            print_r($order);

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
 
    $dorea = new DoreaCashBack();
    $dorea->addCashBackToCart();
    $dorea->addtoCashBack();
    $dorea->checkPlaceOrder();
    $dorea->test();

}

?>