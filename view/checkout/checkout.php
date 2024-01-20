<?php

/**
 * Crypto Cashback Checkout View
 */

require(WP_PLUGIN_DIR . "/dorea/controllers/checkoutController.php");

// woocommerce_after_shop_loop_item_title
// woocommerce_blocks_checkout_enqueue_data
add_action('woocommerce_after_shop_loop_item_title','cashback',10,3);
function cashback(){ 
    $cashback = new cashback();
    $cashbackList = $cashback->list();

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
                               
                               //'/checkout/order-received'
                               let xhr = new XMLHttpRequest();
                               xhr.open('POST', '#', true);
                               xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                               xhr.onreadystatechange = function() {
                                   if (xhr.readyState === 4 && xhr.status === 200) {
                                       //console.log('add to cash back session is set');
                                       //console.log(xhr.responseText);
                                   }
                               };
                              
                               xhr.send('addtoCart=true');
                       
                               // Prevent the form from submitting (optional)
                               return false;
                           }
                       }
           </script>");
} 


/**
* callback function to check session cart page
*/
add_action('wp','checkaddtoCashBack');
function checkaddtoCashBack(){
        
    $checkout = new checkout();
    $checkout->addtoCashback();


}