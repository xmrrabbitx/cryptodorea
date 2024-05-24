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

        $cashback = new cashbackController();
        $cashbackList = $cashback->list();

        if ($cashbackList) {
            // add cash back program element to theme 
            print("<div id='add_to_cashback' style='margin-bottom:10px;padding:5px;padding-left:5px;'>
                        <p>
                            <h4>
                                add to cash back program
                                
            ");
            foreach ($cashbackList as $campaignList) {
                print(" <span>
                            
                            <label>" . $campaignList . "</label>
                            <input id='dorea_add_to_cashback_checkbox' class='dorea_add_to_cashback_checkbox_' type='checkbox' value='" . $campaignList . "' onclick='add_to_cashback_checkbox()'>
                            <input id='dorea_add_to_cashback_address' type='text' oninput='add_to_cashback_checkbox()'>
                            <p id='dorea_add_to_cashback_address_error' style='display:none;color:red;'>wallet address could not be left empty!</p>
                        </span>
                ");

            }
            print("</h4>
                        </p>
                </div>");

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
                                }else{
                                    dorea_add_to_cashback_address.style.border = '1px solid green';
                                      document.getElementById('dorea_add_to_cashback_address_error').style.display = 'none';
                                }
                            }else {
                                // back to normal border 
                                dorea_add_to_cashback_address.style.border = '1px solid black';
                                  document.getElementById('dorea_add_to_cashback_address_error').style.display = 'none';
                            }
                        }
                      
                        //'/checkout/order-received'
                        //application/x-www-form-urlencoded
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
                        
                        if(campaignlist.length > 0 && dorea_add_to_cashback_address.value !== ''){ 
                             
                            let walletAddress = dorea_add_to_cashback_address.value;
                            xhr.send(JSON.stringify({'campaignlist':campaignlist, 'wallet_address':walletAddress}));
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
* callback function to check session cart page
*/
add_action('wp','checkaddtoCashBack');
function checkaddtoCashBack(){

    if (is_page('cart')) {

        $checkout = new checkoutController();
        $checkout->checkout();

    }
  
}


