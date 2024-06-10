<?php

/**
 * Crypto Cashback Checkout View
 */

use Cryptodorea\Woocryptodorea\controllers\cashbackController;
use Cryptodorea\Woocryptodorea\controllers\checkoutController;

// woocommerce_after_shop_loop_item_title
// woocommerce_blocks_checkout_enqueue_data
add_action('woocommerce_blocks_checkout_enqueue_data','cashback',10,3);
function cashback()
{

    if (!WC()->cart->get_cart_contents_count() == 0) {

        // get cashback list of admin
        $cashback = new cashbackController();
        $cashbackList = $cashback->list();

        // get campaign list of user
        $campaign = new checkoutController;
        $campaignListUser = $campaign->list();

        if ($cashbackList) {
            // add cash back program element to theme 
            print("<div id='add_to_cashback' style='margin-bottom:10px;padding:5px;padding-left:5px;'>
                        <p>
                            <h4>
                                add to cash back program:
                                
            ");
            foreach ($cashbackList as $campaignList) {
                if(!in_array($campaignList, $campaignListUser)) {
                    print(" <span>
                            
                            <label>" . $campaignList . "</label>
                            <input id='dorea_add_to_cashback_checkbox' class='dorea_add_to_cashback_checkbox_' type='checkbox' value='" . $campaignList . "' onclick='add_to_cashback_checkbox()'>
                        </span>
                    ");
                }
            }
            if(!$campaign->check($cashbackList)) {
                print("
                    </h4>
                        
                        <input id='dorea_add_to_cashback_address' type='text' placeholder='insert wallet address...' oninput='add_to_cashback_checkbox()'>
                        <p id='dorea_add_to_cashback_address_error' style='display:none;color:red;'>wallet address could not be left empty!</p>

                    </p>
                </div>");
            }else{
                print ("
                    <p>You already joined all cashback programs!</p>
                ");
            }

            // check and add to cash back program
            print("<script>
                    function add_to_cashback_checkbox() {
                        
                        let dorea_add_to_cashback_checked = document.getElementsByClassName('dorea_add_to_cashback_checkbox_');
                        let dorea_add_to_cashback_address = document.getElementById('dorea_add_to_cashback_address');
                          
                   
                             
                        if(dorea_add_to_cashback_checked.length > 0){
                   
                            let campaignlist = [];
                            for(let i=0; i < dorea_add_to_cashback_checked.length;i++){ 
                                if(dorea_add_to_cashback_checked[i].checked){
                                    if(dorea_add_to_cashback_checked[i].value !== ''){
                                            campaignlist.push(dorea_add_to_cashback_checked[i].value);
                                    }
                                    // throw border error on empty address
                                    if(dorea_add_to_cashback_address.value === ''){
                                 
                                         dorea_add_to_cashback_address.style.border = '1px solid red';
                                         document.getElementById('dorea_add_to_cashback_address_error').style.display = 'block';
                                         break;
                                    } else{
                                  
                                         dorea_add_to_cashback_address.style.border = '1px solid green';
                                         document.getElementById('dorea_add_to_cashback_address_error').style.display = 'none';
                                         
                                    }
                                }else {
                                    // throw border error on empty address
                                    if(dorea_add_to_cashback_address.value !== ''){
                                  
                                         dorea_add_to_cashback_address.style.border = '1px solid green';
                                         document.getElementById('dorea_add_to_cashback_address_error').style.display = 'none';
                                         
                                    }else{
                                        // back to normal border 
                                        dorea_add_to_cashback_address.style.border = '1px solid black';
                                        document.getElementById('dorea_add_to_cashback_address_error').style.display = 'none';
                                    }
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
                                 
                            if(campaignlist.length > 0 && dorea_add_to_cashback_address.value !== '' && dorea_add_to_cashback_address.value.length === 42){ 
                                let walletAddress = dorea_add_to_cashback_address.value;
                                xhr.send(JSON.stringify({'campaignlist':campaignlist, 'walletAddress':walletAddress}));
                            }
                            
                            // Prevent the form from submitting (optional)
                            return false;
                        
                                    
                        }
                            
                         
                        
                    }
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
