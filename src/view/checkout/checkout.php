<?php

/**
 * Crypto Cashback Checkout View
 */

use Cryptodorea\Woocryptodorea\controllers\cashbackController;
use Cryptodorea\Woocryptodorea\controllers\checkoutController;
use Cryptodorea\Woocryptodorea\utilities\Encrypt;

// woocommerce_after_shop_loop_item_title
// woocommerce_blocks_checkout_enqueue_data
add_action('woocommerce_blocks_checkout_enqueue_data','cashback',10,3);
function cashback()
{

    var_dump(get_option("dorea_campaignlist_user_" .  wp_get_current_user()->user_login));

    if (!WC()->cart->get_cart_contents_count() == 0) {

        // get cashback list of admin
        $cashback = new cashbackController();
        $cashbackList = $cashback->list();

        // get campaign list of user
        $campaign = new checkoutController;
        $campaignListUser = $campaign->list();

        if ($cashbackList) {

            // check if any campaign funded or not!
            $funded = array_map(function($values){
                if(get_option( $values . '_contract_address')) {

                    return true;

                }
            }, $cashbackList);


            if(!$campaign->check($cashbackList)) {

                print ("
                    <p>You already joined all cashback programs!</p>
                ");

            }elseif(in_array(true, $funded)) {
                // add cash back program element to theme
                print("<div id='add_to_cashback' style='margin-bottom:10px;padding:5px;'>
                        <p>
                            <h4>
                                add to cash back program:
                                <span>
                                    <input id='dorea_walletaddress' type='text' placeholder='your wallet address...' onclick='debouncedAddToCashbackCheckbox()'>
                                </span>
                ");


                foreach ($cashbackList as $campaignList) {
                    // check if campaign is funded
                    //if(get_option( $campaignList . '_contract_address')) {

                    if (!in_array($campaignList, $campaignListUser)) {
                        print(" 
                            <span>
                                <label>" . $campaignList . "</label>
                                <input id='dorea_add_to_cashback_checkbox' class='dorea_add_to_cashback_checkbox_' type='checkbox' value='" . $campaignList . "' onclick='debouncedAddToCashbackCheckbox()'>
                            </span>
                        ");
                    }
                    // }
                }
            }


            print('<p id="dorea_metamask_error" style="display:none;color:#ff5d5d;"></p>');


            // check and add to cash back program
            print("<script>
                     let debounceTimeout;
                    
                        function debounce(func, wait) {
                            return function(...args) {
                                clearTimeout(debounceTimeout);
                                debounceTimeout = setTimeout(() => func.apply(this, args), wait);
                            };
                        }
    
                    function add_to_cashback_checkbox() {
                        
                        let dorea_add_to_cashback_checked = document.getElementsByClassName('dorea_add_to_cashback_checkbox_');
                        let dorea_add_to_cashback_address = document.getElementById('dorea_add_to_cashback_address');
                        
                        let dorea_walletaddress = document.getElementById('dorea_walletaddress');
                        const metamaskError = document.getElementById('dorea_metamask_error');
                            
                        
                        if(dorea_walletaddress.value.length < 42){
                            
                             if (metamaskError.hasChildNodes()) {
                                  metamaskError.removeChild(metamaskError.firstChild);
                             }
                             metamaskError.style.display = 'block';
                             const errorText = document.createTextNode('please insert a valid wallet address!');
                             metamaskError.appendChild(errorText);
                             return false;
                        }else{
                            metamaskError.style.display = 'none';
                            dorea_walletaddress.style.border = '1px solid green'; 
                            if(dorea_add_to_cashback_checked.length > 0){

                                let campaignlist = [];
                                for(let i=0; i < dorea_add_to_cashback_checked.length;i++){ 
                                    if(dorea_add_to_cashback_checked[i].checked){
                                        if(dorea_add_to_cashback_checked[i].value !== ''){
                                                campaignlist.push(dorea_add_to_cashback_checked[i].value);
                                        }
                                        
                                    }else {
                                        
                                    }
                                }
                                
                              
                                // remove wordpress prefix on production
                                let xhr = new XMLHttpRequest();
                                xhr.open('POST', '#', true);
                                xhr.setRequestHeader('Accept', 'application/json');
                                xhr.setRequestHeader('Content-Type', 'application/json');
                                xhr.onreadystatechange = function() {
                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                    
                                        //console.log('add to cash back session is set');
                                       //console.log(xhr.responseText);
                                    }
                                };
                                   
                                if(campaignlist.length > 0){ 
                                    xhr.send(JSON.stringify({'campaignlists':campaignlist,'walletAddress':dorea_walletaddress.value}));
                                }
                                
                                // Prevent the form from submitting (optional)
                                return false;
                        
                                    
                            }
                            
                        }
                        
                    }
                    
                    const debouncedAddToCashbackCheckbox = debounce(add_to_cashback_checkbox, 3000);
                </script>");


        }
    }
}

/**
 * callback function to save session of checkout page
 */
add_action('wp','checkout');
function checkout()
{

    if(is_page('checkout')) {
         $checkout = new checkoutController();
         $checkout->checkout();
    }
}

/**
 * callback function on order received
 */
add_action('woocommerce_thankyou','orderReceived');
function orderReceived($orderId){


    if (is_wc_endpoint_url('order-received')) {

        $checkout = new checkoutController();
        $checkout->orederReceived($orderId);

    }
  
}
