<?php

/**
 * Crypto Cashback Checkout View
 */

require(WP_PLUGIN_DIR . "/dorea/controllers/checkoutController.php");

// woocommerce_after_shop_loop_item_title
// woocommerce_blocks_checkout_enqueue_data
add_action('woocommerce_blocks_checkout_enqueue_data','cashback',10,3);
function cashback(){ 

    wp_enqueue_script('jquery');

    $cashback = new cashback();
    $cashbackList = $cashback->list();

    // add cash back program element to theme 
    print("<div style='margin-bottom:10px;padding:5px;padding-left:5px;'>
                   <p>
                       <h4>
                           add to cash back program
                           
    ");
    foreach($cashbackList as $campaignList){
        print(" <span>
                    
                    <label>".$campaignList."</label>
                        <input class='add_to_cashback_checkbox_' type='checkbox' value=" . $campaignList . " onclick='add_to_cashback_checkbox()'>
                </span>
        ");

    }
    print("</h4>
                   </p>
           </div>");

        // check and add to cash back program
        print("<script>
            function add_to_cashback_checkbox() {
                let add_to_cashback_checkbox_checked = document.getElementsByClassName('add_to_cashback_checkbox_');
                if(add_to_cashback_checkbox_checked.length > 0){
                let campaignList = [];
                for(let i=0; i < add_to_cashback_checkbox_checked.length;i++){  
                    if(add_to_cashback_checkbox_checked[i].checked){
                        if(add_to_cashback_checkbox_checked[i].value !== ''){
                                console.log(add_to_cashback_checkbox_checked[i].value);
                                campaignList.push(add_to_cashback_checkbox_checked[i].value);
                        }
                    }
                }
                   
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
                    
                if(campaignList.length > 0){ 
                    xhr.send('campaignList='+campaignList);
                }
                // Prevent the form from submitting (optional)
                return false;
                
               
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
    $checkout->checkout();

}


