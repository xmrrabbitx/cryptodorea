<?php
/*
Plugin Name: Dorea CashBack
Description: A New way of cash back to you loyal customers
Version: 1.0.0
*/

defined( 'ABSPATH' ) || exit;

define( 'DoreaCashBack_VERSION', '1.0.0' );
define( 'DoreaCashBack_URI', plugin_dir_url( __FILE__ ) );

include_once('abstractDorea.php');
include_once('DoreaDB.php');


/**
 * 
 */
class DoreaCashBack extends abstractDorea{

    public function __construct(){


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

    public function checkCashBackToCart(){
        add_action('wp','check');
        function check(){
            session_start();

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cartSession'])) {
                
                $_SESSION['cartSession'] = true;

            }

           //unset($_SESSION['cartSession']);
        }

    }

    
    public function checkPlaceOrder(){

        add_action('woocommerce_thankyou','isPaid');
        function isPaid($order_id){

            print_r($order_id);

            // Check if the order has been paid
            if($order_id){
                $order = wc_get_order($order_id);
                print_r($order);
    
                $user = $order->get_user();
                $userName = $user->user_login;
                $displayName = $user->display_name;
                $userEmail = $user->user_email; 
                print_r($displayName);

                // store data into sqlite database
                /**
                 * @param $order_id
                 * @param $userName
                 * @param $displayName
                 * @param $userEmail
                */
                


                // remove any session user data
                unset($_SESSION['cartSession']);
            }
            
    
        }    

    }

    
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    if (!class_exists('WooCommerce')) {
       
        include_once ABSPATH . 'wp-content/plugins/woocommerce/woocommerce.php'; 
    }
 
    $dorea = new DoreaCashBack();
    $dorea->addCashBackToCart();
    $dorea->checkCashBackToCart();
    $dorea->checkPlaceOrder();



}

?>