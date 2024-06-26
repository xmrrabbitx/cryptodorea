<?php

/**
 * Enqueue styles for the plugin
 */
function paymentModal_styles() {
    //wp_enqueue_style('dorea_payment_modal_styles', plugin_dir_url('dorea/view') . 'view/style/paymentModal.css');
}
// input must be after scripts
add_action('wp_enqueue_scripts', 'paymentModal_styles');

/**
 * a payment modal to get wallet address and pay to user
 */
add_action('wp', 'paymentModal');
function paymentModal(){

    $contractAddress = null;
    $amount = null;
    $secret = null;
    $campaignCount = null;
    $shoppingCount = null;
    $campaignInfoUser = get_option('dorea_campaigninfo_user');
    if($campaignInfoUser) {
        foreach ($campaignInfoUser as $keys => $values) {
            $campaign = get_transient($keys);

            if ($values['count'] >= $campaign['shoppingCount']) {

                $contractAddress = get_option($keys . '_contract_address');
                $secret = get_option($keys . '_secretHash');
                $campaignCount = $campaign['shoppingCount'];
                $shoppingCount = $values['count'];

                $amount = $campaign['cryptoAmount'];

                // Payment Modal
                print('
                <div id="doreaPaymentModalContainer" class="dorea-payment-modal-container">
                        <button id="'.$keys.'" class="_claimCashback" type="button">Claim '.$keys.' Cashback</button>
                        <input id="'.$keys.'_contractAddress" type="hidden" value="'.$contractAddress.'" >
                </div>
                
                <p id="dorea_metamask_error" style="display:none;color:#ff5d5d;"></p>
            ');
            }
        }
    }

    print('
       <script type="module">
        
            import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js";

            let doreaPaymentModalButton = document.querySelectorAll("._claimCashback");

            doreaPaymentModalButton.forEach(
                
                (element) =>             
           
                element.addEventListener("click", function(){
                  
                setTimeout(delay, 500)
                function delay(){
                 (async () => {
                
                 if (window.ethereum) {
                
                        
                         let contractAddress = document.getElementById(element.id + "_contractAddress").value;
                         const metamaskError = document.getElementById("dorea_metamask_error");
                            
                                      // change it to the real polygon network
                                      
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
                                      
                                       /*
                                       await window.ethereum.request({
                                          method: "wallet_addEthereumChain",
                                          params: [{
                                            chainId: "0x14A34",
                                            rpcUrls: ["https://base-sepolia.blockpi.network/v1/rpc/public"],
                                            chainName: "Amoy",
                                            nativeCurrency: {
                                              name: "MATIC",
                                              symbol: "MATIC",
                                              decimals: 18
                                            },
                                            blockExplorerUrls: ["https://base-sepolia.blockscout.com"]
                                          }]
                                       });
                                       
                                        */
                                      
                                       const accounts = await window.ethereum.request({method: "eth_requestAccounts"});
    
                                    // get abi and bytecode
                                    let xhr = new XMLHttpRequest();
                            
                                    // remove wordpress prefix on production
                                    xhr.open("GET", "/wordpress/wp-admin/admin-post.php?action=loyalty_json_file", true);
                                    xhr.onreadystatechange = async function() {
                                        if (xhr.readyState === 4 && xhr.status === 200) {
                                                let response = JSON.parse(xhr.responseText);
                                                let abi = response[0]
                                                let bytecode = response[1]
                                      
                                           
                                           
                                           const accounts = await window.ethereum.request({method: "eth_requestAccounts"});
                                           const userAddress = accounts[0];
                                          
                                           
                                           const userBalance = await window.ethereum.request({
                                                 method: "eth_getBalance",
                                                params: [userAddress, "latest"]
                                           });
                                           
                                           // check balance of metamask wallet 
                                            if(userBalance < 0.003){
                                            
                                                metamaskError.style.display = "block";
                                                const errorText = document.createTextNode("not enough balance to support fee. please fund your wallet at least 0.003 ETH!");
                                                metamaskError.appendChild(errorText);
                                                return false;
                                            
                                            }else{
                                                metamaskError.style.display = "none";
                                            }
                           
                                        const provider = new BrowserProvider(window.ethereum);
                            
                                        // Get the signer from the provider metamask
                                        const signer = await provider.getSigner();
                                 
                                        const contract = new ethers.Contract(contractAddress, abi, signer);
                           
                                        const balance = await contract.getBalance();
                                      
                                        if(balance !== 0n){
                                            
                                            metamaskError.style.display = "none";
                                            
                                            try{
                                                
                                                await contract.pay(userAddress.toString(), BigInt("'.$amount.'" / 0.000000000000000001).toString(), "'.$campaignCount.'", "'.$shoppingCount.'", "'.$secret.'")
                                                //await contract.pay(userAddress.toString(), BigInt("'.$amount.'" / 0.000000000000000001), 3, "'.$shoppingCount.'", "'.$secret.'");
                                                
                                            }catch (error) {
                                             
                                                console.log(error)
                                                // show error popup message
                                                metamaskError.style.display = "block";
                                                const errorText = document.createTextNode(error.message);
                                                metamaskError.appendChild(errorText);
                                                return false;
                                            }
                                            
                                        }else{
                                          
                                             
                                            // show error popup message
                                              metamaskError.style.display = "block";
                                              const errorText = document.createTextNode("Sorry, this campaign reached to the end!");
                                              metamaskError.appendChild(errorText);
                                              return false;
                                        }
                                        
                                         
                                        
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