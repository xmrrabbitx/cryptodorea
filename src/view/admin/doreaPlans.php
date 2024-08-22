<?php

use Cryptodorea\Woocryptodorea\controllers\adminStatusController;
use Cryptodorea\Woocryptodorea\controllers\freetrialController;
use Cryptodorea\Woocryptodorea\utilities\plansCompile;

//user plan contract address
const doreaUserContractAddress = "0x870ACBEd09BC6f6AA27B252262A475F5c09DC340";

/**
 * Crypto Cashback Plans
 */
function doreaPlans()
{

    $plansCompile = new plansCompile();
    $abi = $plansCompile->abi();
    //$bytecode = $plansCompile->bytecode();

    // check how many days remianed on free trial plan
    $freetrial = new  freetrialController();
    $remainedDays = $freetrial->remainedDays();

    if($remainedDays !== 0){
        print('You have ' . $remainedDays . 'days of free trial');
    }


    print('
        <style>
            body{
                background: #f6f6f6 !important;
            }
            main{
                font-family: "Poppins", sans-serif !important;
            }
        </style>
    ');

    print("<main>");
    print("<h1 class='p-5 text-sm font-bold'>Dorea Plans</h1> </br>");

    print("<div class='!container !mx-auto pl-5 pt-2 pb-5 shadow-transparent rounded-md'>");

    print("<button class='text-center' id='doreaMetamask'>connect to Metamask</button>");


    print("
        <div class='!grid !grid-cols-3 !gap-5'>
           <div class='!p-5 !bg-[#ECECEC] !rounded-md'>
               <header class='!text-sm !font-bold'>Monthly</header>
               <p class='!text-xl !mt-5'>$29 USD</p>
               <p class='!text-sm !mt-5'>Perfect for starting</p>
               
               <div class='!text-sm !mt-5'>
                    <p class='!flex !flex-grid'>
                        <svg class='!size-4 !text-black-500 !mt-[1px]' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                          <path fill-rule='evenodd' d='M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z' clip-rule='evenodd' />
                        </svg>
                        <span class='!text-sm !pl-1'>
                            Create Cashback 
                        </span>
                    </p>
                    <p class='!flex !flex-grid'>
                        <svg class='!size-4 !text-black-500 !mt-[1px]' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                          <path fill-rule='evenodd' d='M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z' clip-rule='evenodd' />
                        </svg>
                        <span class='!text-sm !pl-1'>
                            Managing Loyal Customers
                        </span>
                    </p>
                    <p class='!flex !flex-grid'>
                        <svg class='!size-4 !text-black-500 !mt-[1px]' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                          <path fill-rule='evenodd' d='M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z' clip-rule='evenodd' />
                        </svg>
                        <span class='!text-sm !pl-1'>
                            Pay with Ethereum
                        </span>
                    </p>
               </div>
               <div class='!text-center !mt-5'>
                    <button  id='doreaBuy_monthly' class='doreaBuy !w-32 !bg-[#faca43] !rounded-md !p-3 !mt-5'  value='19_Monthly'>get started</button>
                </div>
           </div>
           <div>
               <header>6 Months</header>
               <button  id='doreaBuy_halfYearly' class='doreaBuy'  value='29_halfYearly'>buy</button>
           </div><div>
              <header>Yearly</header>
              <button  id='doreaBuy_Yearly' class='doreaBuy'  value='49_Yearly'>buy</button>
           </div>
       </div>
    ");


    print('<script type="module">

         import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js";
            
            let doreaMetamask = document.getElementById("doreaMetamask");
            
            let doreaBuy_monthly = document.getElementById("doreaBuy_monthly");
            let doreaBuy_halfYearly = document.getElementById("doreaBuy_halfYearly");
            let doreaBuy_Yearly = document.getElementById("doreaBuy_Yearly");
          
            if (window.ethereum) {
               setTimeout(delay, 1000)
               function delay(){
                    (async () => {
                     if(window.ethereum._state.accounts.length > 0){

                         doreaMetamask.style.display = "none";
                         
                         doreaBuy_monthly.style.display = "block";
                         doreaBuy_halfYearly.style.display = "block";
                         doreaBuy_Yearly.style.display = "block";
                      
                     }else{
                           
                            doreaMetamask.addEventListener("click", async function(){
                         
                                     const accounts = await window.ethereum.request({method: "eth_requestAccounts"});
                                     const userAddress = accounts[0];
                                     
                                     const provider = new BrowserProvider(window.ethereum);
                                                    
                                     // Get the signer from the provider metamask
                                     const signer = await provider.getSigner();

                                     const contract = new ethers.Contract("'.doreaUserContractAddress.'", '.$abi.', signer);
                                                 
                                     try{
                                         let userStatusPlan = await contract.userCheckStatus(userAddress);
                                         
                                          let xhrUserStatusPlan = new XMLHttpRequest();
                                          xhrUserStatusPlan.open("POST", "#", true);
                                          xhrUserStatusPlan.setRequestHeader("Accept", "application/json");
                                          xhrUserStatusPlan.setRequestHeader("Content-Type", "application/json");
                                          xhrUserStatusPlan.onreadystatechange = function() {
                                            if (xhrUserStatusPlan.readyState === 4 && xhrUserStatusPlan.status === 200) {
            
                                                 doreaMetamask.style.display = "none";
                                                 
                                                 doreaBuy_monthly.style.display = "block";
                                                 doreaBuy_halfYearly.style.display = "block";
                                                 doreaBuy_Yearly.style.display = "block";
                                            }
                                          };
                                           
                                          xhrUserStatusPlan.send(JSON.stringify({"userStatus":BigInt(userStatusPlan[0]).toString(), "userAmount":BigInt(userStatusPlan[1]).toString(), "expDate":BigInt(userStatusPlan[2]).toString()}));
                                        
                                          // remove wordpress prefix on production 
                                          //window.location.replace("/wordpress/wp-admin/admin.php?page=credit");
                                     }catch(error){
                                            console.log(error)
                                     }
                                             
                            })
                        }
                  })();
               }
            }


            let doreaPaymentModalButton = document.querySelectorAll(".doreaBuy");

             doreaPaymentModalButton.forEach(
                
                (element) =>             
           
                    element.addEventListener("click", function(){
                      // Request access to Metamask
                     setTimeout(delay, 1000)
                     function delay(){
                         (async () => {
                      
                                 let amount =  element.value.split("_")[0];
                                 let planType =   element.value.split("_")[1];
                             
                                 if (window.ethereum) {
                                              
                                            const accounts = await window.ethereum.request({method: "eth_requestAccounts"});
                                            const userAddress = accounts[0];
                                            
                                            // get abi and bytecode
                                            let xhr = new XMLHttpRequest();
                             
                                                    const userBalance = await window.ethereum.request({
                                                         method: "eth_getBalance",
                                                        params: [userAddress, "latest"]
                                                    });
                                                    
                                                    
            
                                                    // check balance of metamask wallet 
                                                   // if(parseInt(userBalance) < 300000000000000){
                                                        
                                                   
                                                        
                                                   // }else{
                                                      
                                                   // }
                                                   
                                                   //let xhrAmount = new XMLHttpRequest();
                                                   
                                                   // converter issue must be fixed! price is not precise!
                                                  // xhrAmount.open("GET","https://vip-api.changenow.io/v1.6/exchange/estimate?fromCurrency=usdt&fromNetwork=eth&fromAmount="+amount+"&toCurrency=eth&toNetwork=eth&type=direct&promoCode=&withoutFee=false");
                                                  // xhrAmount.onreadystatechange = async function() {
                                                        //if (xhrAmount.readyState === 4 && xhrAmount.status === 200) {
                                                            // let responses = JSON.parse(xhrAmount.responseText);
                                                             let estimatedAmount = 0.0072;//responses["summary"]["estimatedAmount"]
                                                    let contractAmountBigInt;
                                                    if((Number.isInteger(estimatedAmount))){
                                                        const creditAmountBigInt = BigInt(estimatedAmount);
                                                        const multiplier = BigInt(1e18);
                                                        contractAmountBigInt= creditAmountBigInt * multiplier;
                                                      
                                                    }else{
                                                    
                                                        const creditAmount = estimatedAmount; // This is a floating-point number
                                                        const multiplier = BigInt(1e18); // This is a BigInt
                                                        const factor = 1e18; // Use the same factor as the multiplier to avoid precision issues
                                                        
                                                        // Convert the floating-point number to an integer
                                                        const creditAmountInt  = BigInt(Math.round(creditAmount * factor));
                                                        contractAmountBigInt= creditAmountInt * multiplier / BigInt(factor);
                                              
                                                    }
                                                  
                                                            const provider = new BrowserProvider(window.ethereum);
                                                
                                                            // Get the signer from the provider metamask
                                                            const signer = await provider.getSigner();
                                                           
                                                            const contract = new ethers.Contract("'.doreaUserContractAddress.'", '.$abi.', signer);
                                               
                                                            let latestPrice = await contract.latestPrice();
                                                            latestPrice = latestPrice.toString();
                                 
                                                            let price = BigInt(parseInt(19 * 1000000000 / parseInt(latestPrice.slice(0, 4)) * 1000000000));
                                                            
                                                            try{
                                                                
                                                                await contract.pay( userAddress, planType, {
                                                                    value:price.toString()
                                                                }).then(async function(transaction){
                                                         
                                                                    if(transaction.hash){
                                                                       let receipt = await transaction.wait();
                                                                        if(receipt.status === 1){
                                                                            
                                                                           //send status info 
                                                                           let userStatusPlan = await contract.userCheckStatus(userAddress);
                                                                           let xhrUserStatusPlan = new XMLHttpRequest();
                                                                           xhrUserStatusPlan.open("POST","#");
                                                                           xhrUserStatusPlan.send(JSON.stringify({paymentStatus:Number(userStatusPlan[0]), paymentAmount:Number(userStatusPlan[1]), expDate:Number(userStatusPlan[2])}));

                                                                        }
                                                                    }
                                                                  
                                                                });
                                                                
                                                              
                                                            }catch(error){
                                                                console.log(error)
                                                            }
                                                            
                                                             
                                                    
                                                     // }
                                                   //}
                                                   //xhrAmount.send();
                           
                            
                                        }
            
                          })();
                      }
                      
                })

             )
             
        </script>
    ');


    print("
            </div>
        </main>
    ");
    
}

/**
 * Dorea Plans Loyalty Users ABI and Bytecode of smart contract
 */
add_action('admin_post_loyalty_users_json_file', 'dorea_admin_loyalty_users_json_file');
function dorea_admin_loyalty_users_json_file()
{

    $loyaltyJson = file_get_contents(WP_PLUGIN_DIR . '/woo-cryptodorea/users.json');
    $compiledContract = json_decode($loyaltyJson);

    $abi = $compiledContract->abi;
    $bytecode = $compiledContract->bytecode->object;

    $responseArray = [$abi, $bytecode];
    header('Content-Type: application/json');

    // Echo the JSON-encoded response
    echo json_encode($responseArray);
    exit;

}

/**
 * Dorea Plans check User Admin Status payment
 */
add_action('admin_menu', 'dorea_admin_status_payment');

function dorea_admin_status_payment()
{

    $jsonData = file_get_contents('php://input');
    $json = json_decode($jsonData);

    if(isset($json)) {
        // set timestamp for expire admin user
        $userPayment = new adminStatusController();
        $userPayment->set($json->expDate);
    }
}

/**
 * Dorea Plans check Free Trial Period
 */
add_action('admin_menu', 'dorea_free_trial');
function dorea_free_trial(){

    $freetrial = new  freetrialController();
    $freetrial->set();

    $userPayment = new adminStatusController();

    if(isset($_GET['page'])){
        if($_GET['page'] !== 'dorea_plans'){
            if(!$userPayment->paid()) {
                $freetrial->expire();
            }
        }
    }
}