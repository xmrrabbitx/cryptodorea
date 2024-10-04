<?php

use Cryptodorea\Woocryptodorea\controllers\cashbackController;
use Cryptodorea\Woocryptodorea\controllers\checkoutController;

add_action('woocommerce_blocks_checkout_enqueue_data','cashback', 10, 3);
/**
 * Crypto Cashback Checkout View
 */
function cashback(): void
{

    if (!WC()->cart->get_cart_contents_count() == 0) {

        // get cashback list of admin
        $cashback = new cashbackController();
        $cashbackList = $cashback->list();

        // get campaign list of user
        $checkoutController = new checkoutController;

        $diffCampaignsList = $checkoutController->check($cashbackList);

        if ($cashbackList) {

            if(empty($diffCampaignsList)) {

                print ("
                    <p>You already joined all cashback programs!</p>
                ");

            }else {
                $addtoCashback = true;
                // show campaigns in view
                if (!empty($cashbackList)) {
                    foreach ($diffCampaignsList as $campaign) {
                        // check if any campaign funded or not!
                        if (get_option($campaign . '_contract_address')) {

                            // check if campaign started or not
                            if($checkoutController->expire($campaign)) {

                                // add to cash back program option
                                if ($addtoCashback) {
                                    print("
                                  <div id='add_to_cashback' style='margin-bottom:10px;padding:5px;'>
                                     <p>
                                        <h4>
                                           add to cash back program:
                                           <span>
                                               <input id='dorea_walletaddress' type='text' placeholder='your wallet address...' >
                                           </span>
                                ");
                                    $addtoCashback = false;
                                }

                                $campaignLable = explode("_", $campaign)[0];
                                print(" 
                                  <span>
                                     <label>" . $campaignLable . "</label>
                                     <input id='dorea_add_to_cashback_checkbox' class='dorea_add_to_cashback_checkbox_' type='checkbox' value='" . $campaign . "'>
                                  </span>
                               ");

                            }

                        }
                    }
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
                    
                    let dorea_walletaddress = document.getElementById('dorea_walletaddress');
                    let dorea_add_to_cashback_checkbox = document.querySelectorAll('.dorea_add_to_cashback_checkbox_');
                    let metamaskError = document.getElementById('dorea_metamask_error');   
                      
                    let campaignlist = []; 
                            
                    dorea_walletaddress.addEventListener('input',function (){
                      setTimeout(() => {  
                        if(dorea_walletaddress.value.length !== 0){
                            if(dorea_walletaddress.value.length < 42){
                                 if (metamaskError.hasChildNodes()) {
                                      metamaskError.removeChild(metamaskError.firstChild);
                                 }
                                 metamaskError.style.display = 'block';
                                 dorea_walletaddress.style.border = '1px solid red'; 
                                 const errorText = document.createTextNode('please insert a valid wallet address!');
                                 metamaskError.appendChild(errorText);
                                 return false;
                            }else if(dorea_walletaddress.value.slice(0,2) !=='0x'){
                                 if (metamaskError.hasChildNodes()) {
                                      metamaskError.removeChild(metamaskError.firstChild);
                                 }
                                 metamaskError.style.display = 'block';
                                 dorea_walletaddress.style.border = '1px solid red'; 
                                 const errorText = document.createTextNode('wallet address must start with 0x phrase!');
                                 metamaskError.appendChild(errorText);
                                 return false;
                            }else{
                                 setSession();
                                sessionStorage.setItem('walletAddress', dorea_walletaddress.value);
                                metamaskError.style.display = 'none';
                                dorea_walletaddress.style.border = '1px solid green'; 
                            }
                        }else{
                             metamaskError.style.display = 'none';
                             dorea_walletaddress.style.border = '1px solid #ccc'; 
                        }
                      },1000);
                    })
                    
                    dorea_add_to_cashback_checkbox.forEach(
                
                        (element) =>   
                          element.addEventListener('click', async function(){
                              
                              if(element.checked){
                                 if(!campaignlist.includes(element.value)){
                                        campaignlist.push(element.value);
                                        setSession();
                                 }
                              }else{
                                        campaignlist = campaignlist.filter(function (letter) {
                                        return letter !== element.value;
                                  });
                              }
                               
                          })
                    )
                  
                  function setSession(){
                       if(campaignlist.length > 0 && dorea_walletaddress.value.length > 0){
                           let data = JSON.stringify({'campaignlists':campaignlist,'walletAddress':dorea_walletaddress.value});
                           sessionStorage.setItem('doreaCampaignInfo',data);
                       }    
                       
                  }
             
                  
                    /*
                     dorea_add_to_cashback_checked.addEventListener('click',function (){
                         console.log('click')
                         if(dorea_add_to_cashback_checked.length < 1){
                             
                             metamaskError.style.display = 'block';
                             dorea_walletaddress.style.border = '1px solid red'; 
                             const errorText = document.createTextNode('please choose one of compaigns!');
                             metamaskError.appendChild(errorText);
                             return false;
                         }else{
                             let campaignlist = [];
                                for(let i=0; i < dorea_add_to_cashback_checked.length;i++){ 
                                    if(dorea_add_to_cashback_checked[i].checked){
                                        if(dorea_add_to_cashback_checked[i].value !== ''){
                                                campaignlist.push(dorea_add_to_cashback_checked[i].value);
                                        }
                                        
                                    }else {
                                        
                                    }
                                }
                                console.log(campaignlist)
                         }
                     })
                     
                     */
                    
                    function add_to_cashback_checkbox() {
                    
                         return  false
                        let dorea_walletaddress = document.getElementById('dorea_walletaddress');
                        const metamaskError = document.getElementById('dorea_metamask_error');
                         
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
                              
                                if(campaignlist.length > 0){ 
                                    xhr.send(JSON.stringify({'campaignlists':campaignlist,'walletAddress':dorea_walletaddress.value}));
                                }
                                
                                // Prevent the form from submitting (optional)
                                return false;
                        
                        }
                            
                    }
                    
                     const debouncedAddToCashbackCheckbox = debounce(add_to_cashback_checkbox, 3000);
                    
                </script>");
        }
    }
}

/**
 * callback function on order received
 */
add_action('woocommerce_thankyou','orderReceived');
function orderReceived($orderId):void{

    if (is_wc_endpoint_url('order-received')) {

        $order = json_decode(new WC_Order($orderId));

        if(isset($order->id)) {

            // send session doreaCampaignInfo to checkout controller
            print ('
                <script>
                    let campaignInfo = JSON.parse(sessionStorage.getItem("doreaCampaignInfo"));
                    
                    // remove wordpress prefix on production
                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "#", true);
                    xhr.setRequestHeader("Accept", "application/json");
                    xhr.setRequestHeader("Content-Type", "application/json");
                    
                    xhr.onreadystatechange = async function() {
                       if (xhr.readyState === 4 && xhr.status === 200) {
                                                                    
                           sessionStorage.removeItem("doreaCampaignInfo")                                      
                       }
                    }
                    if(campaignInfo !== null){
                        xhr.send(JSON.stringify({"campaignlists":campaignInfo.campaignlists,"walletAddress":campaignInfo.walletAddress}));
                    }
                </script>
            ');

            // get Json Data
            $data = file_get_contents('php://input');
            $json = json_decode($data) ?? null;

            if (!empty($json)) {
                // save doreaCampaignInfo
                $checkout = new checkoutController();

                // check if campaign
                $statusCampaigns = [];
                $campaignLists = (array)$json->campaignlists;
                foreach ($campaignLists as $campaign){

                    $statusCampaigns[] = $checkout->expire($campaign);

                }
                if(in_array(true, $statusCampaigns)){
                    $checkout->autoRemove();
                    $checkout->checkout($json);
                }else{
                    wp_redirect('/');
                }
            }

            // receive order details
            $checkout = new checkoutController();
            $checkout->orederReceived($order,$orderId);
        }
    }
}
