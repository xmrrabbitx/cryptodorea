<?php


use Cryptodorea\Woocryptodorea\controllers\adminStatusController;
use Cryptodorea\Woocryptodorea\controllers\freetrialController;

const doreaUserContractAddress = "0xBCdBdF2E097C59bBFDD27ab86AFaf40577ce4529";

/**
 * Crypto Cashback Plans
 */

function doreaPlans()
{

    print ("plans page");

    // check how many days remianed on free trial plan
    $freetrial = new  freetrialController();
    $remainedDays = $freetrial->remainedDays();

    if($remainedDays !== 0){
        print('You have ' . $remainedDays . 'days of free trial');
    }


    print("<button id='doreaMetamask'>connect to Metamask</button>");


    print("<head>Monthly</head>");

    print("<head>6 Months</head>");

    print("<head>Yearly</head>");

    print ('

        <button class="doreaBuy"  value="19_Monthly">buy</button>
        <button class="doreaBuy"  value="29_hlafYearly">buy</button>
        <button class="doreaBuy"  value="49_Yearly">buy</button>

    ');

    print('<script type="module">
         import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js";

            if (window.ethereum) {
               setTimeout(delay1, 1000)
               function delay1(){
                    (async () => {
                     if(window.ethereum._state.accounts.length > 0){
                      
                         doreaMetamask.style.display = "none";
                     }else{
                   
                            let doreaMetamask = document.getElementById("doreaMetamask");
                            doreaMetamask.addEventListener("click", function(){
                         
                              let userStatusXhr = new XMLHttpRequest();
                              userStatusXhr.open("GET", "/wordpress/wp-admin/admin-post.php?action=loyalty_users_json_file", true);
                              userStatusXhr.onreadystatechange = async function() {
                                  if (userStatusXhr.readyState === 4 && userStatusXhr.status === 200) {
                                     let response = JSON.parse(userStatusXhr.responseText);
                                     let abi = response[0]
                                     let bytecode = response[1]
                                     
                                     const accounts = await window.ethereum.request({method: "eth_requestAccounts"});
                                     const userAddress = accounts[0];
                                     
                                     const provider = new BrowserProvider(window.ethereum);
                                                    
                                     // Get the signer from the provider metamask
                                     const signer = await provider.getSigner();
                                                    
                                     const contract = new ethers.Contract("'.doreaUserContractAddress.'", abi, signer);
                                     
                                                             
                                     try{
                                         let userStatusPlan = await contract.userCheckStatus(userAddress);
                                         
                                          //return userStatusPlan
                                          let xhrUserStatusPlan = new XMLHttpRequest();
                                          xhrUserStatusPlan.open("POST", "#", true);
                                          xhrUserStatusPlan.setRequestHeader("Accept", "application/json");
                                          xhrUserStatusPlan.setRequestHeader("Content-Type", "application/json");
                                          xhrUserStatusPlan.onreadystatechange = function() {
                                            if (xhrUserStatusPlan.readyState === 4 && xhrUserStatusPlan.status === 200) {
            
                                                
                                            }
                                          };
                                           
                                          xhrUserStatusPlan.send(JSON.stringify({"userStatus":BigInt(userStatusPlan[0]).toString(), "userAmount":BigInt(userStatusPlan[1]).toString(), "expDate":BigInt(userStatusPlan[2]).toString()}));
                                        
                                          doreaMetamask.style.display = "none";
                                          // remove wordpress prefix on production 
                                          //window.location.replace("/wordpress/wp-admin/admin.php?page=credit");
                                     }catch(error){
                                            console.log(error)
                                     }
                                      
                                  }
                              } 
                              
                              userStatusXhr.send();                     
                            })
                        }
                  })();
               }
            }

            
            let doreaPaymentModalButton = document.querySelectorAll(".doreaBuy");
            console.log(doreaPaymentModalButton)
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
                                              
                                              // change it to the real polygon network
                                              /*
                                               await window.ethereum.request({
                                                  method: "wallet_addEthereumChain",
                                                  params: [{
                                                    //chainId: "0x2105",
                                                    chainId: "0xE705",
                                                    //rpcUrls: ["https://base.blockpi.network/v1/rpc/public"],
                                                    rpcUrls: ["https://linea-sepolia.blockpi.network/v1/rpc/public"],
                                                    chainName: "Base",
                                                    nativeCurrency: {
                                                      name: "ETH",
                                                      symbol: "ETH",
                                                      decimals: 18
                                                    },
                                                    blockExplorerUrls: ["https://etherscan.io/"]
                                                  }]
                                              });
                                              */
                                              /*
                                              await window.ethereum.request({
                                                  method: "wallet_addEthereumChain",
                                                  params: [{
                                                    chainId: "0x14A34",
                                                    rpcUrls: ["https://base-sepolia.blockpi.network/v1/rpc/public"],
                                                    chainName: "SEPOLIA",
                                                    nativeCurrency: {
                                                      name: "ETH",
                                                      symbol: "ETH",
                                                      decimals: 18
                                                    },
                                                    blockExplorerUrls: ["https://base-sepolia.blockscout.com"]
                                                  }]
                                              });
                                              
                                              */
                                              
                                              await window.ethereum.request({
                                                  method: "wallet_addEthereumChain",
                                                  params: [{
                                                    chainId: "0x539",
                                                    rpcUrls: ["http://127.0.0.1:8545"],
                                                    chainName: "Ganache Testnet",
                                                    nativeCurrency: {
                                                      name: "ETH",
                                                      symbol: "ETH",
                                                      decimals: 18
                                                    },
                                                    blockExplorerUrls: ["http://127.0.0.1:8545"]
                                                  }]
                                              });
                                              
                                               
                                            const accounts = await window.ethereum.request({method: "eth_requestAccounts"});
            
                                            // get abi and bytecode
                                            let xhr = new XMLHttpRequest();
                                            
                                            // remove wordpress prefix on production
                                            xhr.open("GET", "/wordpress/wp-admin/admin-post.php?action=loyalty_users_json_file", true);
                                            xhr.onreadystatechange = async function() {
                                                if (xhr.readyState === 4 && xhr.status === 200) {
                                                        let response = JSON.parse(xhr.responseText);
                                                        let abi = response[0]
                                                        let bytecode = response[1]
                                                  
                                                   
                                                    const accounts = await window.ethereum.request({method: "eth_requestAccounts"});
                                                    const userAddress = accounts[0];
                                                  
                                                   /*
                                                    const userBalance = await window.ethereum.request({
                                                         method: "eth_getBalance",
                                                        params: [userAddress, "latest"]
                                                    });
                                                    
                                                    */
            
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
                                                             let estimatedAmount = "0.006";//responses["summary"]["estimatedAmount"]
                                                  
                                                   
                                                            const provider = new BrowserProvider(window.ethereum);
                                                
                                                            // Get the signer from the provider metamask
                                                            const signer = await provider.getSigner();
                                                
                                                            const contract = new ethers.Contract("'.doreaUserContractAddress.'", abi, signer);
                                               
                                                            try{
                                                                await contract.pay( userAddress, planType, {
                                                                value:BigInt(estimatedAmount / 0.000000000000000001).toString()
                                                            }).then(function(transaction){
                                                                
                                                                if(transaction.hash){
                                                                    console.log("payment succeed!")
                                                                }
                                                                
                                                                
                                                            });
                                                            }catch(error){
                                                                console.log(error)
                                                            }
                                                    
                                                     // }
                                                   //}
                                                   //xhrAmount.send();
                                          }
                                   
                            };
                            
                            xhr.send();
                            
                                       
                                    
                                        }
            
            
                         
                      
                          })();
                      }
                      
                })

             )
        </script>
    ');
    
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

    //delete_option("adminPaymentTimestamp");
    //delete_option("trialTimestamp");
    //var_dump(get_option("adminPaymentTimestamp"));
    //var_dump(get_option("trialTimestamp"));

    $userPayment = new adminStatusController();

    if($_GET['page'] !== 'dorea_plans'){
        if(!$userPayment->paid()) {
            $freetrial->expire();
        }
    }
}