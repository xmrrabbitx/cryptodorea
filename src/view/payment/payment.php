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
    $campaignInfoUser = get_option('dorea_campaigninfo_user');
    if($campaignInfoUser) {
        foreach ($campaignInfoUser as $keys => $values) {
            $campaign = get_transient($keys);

            if ($values['count'] >= $campaign['shoppingCount']) {

                $contractAddress = get_option($keys . '_contract_address');

                $amount = $campaign['cryptoAmount'];

                // Payment Modal
                print('
                <div id="doreaPaymentModalContainer" class="dorea-payment-modal-container">
                        <button id='.$keys.' class="_claimCashback" type="button">Claim '.$keys.' Cashback</button>
                        <input id='.$keys.'_contractAddress type="hidden" value="'.$contractAddress.'" >
                </div>
            ');
            }
        }
    }

    print('
       <script type="module">
        
            import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js";

            let doreaPaymentModalButton = document.querySelectorAll("._claimCashback");
            console.log(doreaPaymentModalButton)
            doreaPaymentModalButton.forEach(
                
                (element) =>             
           
                element.addEventListener("click", function(){
                  
                setTimeout(delay, 500)
                function delay(){
                 (async () => {
                
                 if (window.ethereum) {
                
                                      
                         let conttractAddress = document.getElementById(element.id + "_contractAddress").value;
         
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
                                            //console.log(bytecode)
                                           
                                           
                                           const accounts = await window.ethereum.request({method: "eth_requestAccounts"});
                                           const userAddress = accounts[0];
                                          
                                           /*
                                           const userBalance = await window.ethereum.request({
                                                 method: "eth_getBalance",
                                                params: [userAddress, "latest"]
                                           });
                                            */
                           

                                        const provider = new BrowserProvider(window.ethereum);
                            
                                        // Get the signer from the provider metamask
                                        const signer = await provider.getSigner();
                           
                                        const contract = new ethers.Contract(conttractAddress, abi, signer);
                           
                                        const balance = await contract.get();
                                        
                                        console.log(balance)
                                        
                                        const trxResult = await contract.pay([userAddress.toString()], BigInt("'.$amount.'" / 0.000000000000000001).toString());
                                        
                                       // window.location.replace("/wordpress/wp-admin/admin.php?page=credit");
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
 * get contract address to claim cashback
 */
add_action('wp', 'dorea_claim_contract_address');

function dorea_claim_contract_address()
{

    $contractAddress = get_option('dorea_contract_address');
    $responseArray = [$contractAddress];
    //header('Content-Type: application/json');

    // Echo the JSON-encoded response
    return json_encode($responseArray);

}