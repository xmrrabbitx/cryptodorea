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

    $campaignInfoUser = get_option('dorea_campaigninfo_user');

    foreach ($campaignInfoUser as $keys=>$values){
        $campaign = get_transient($keys);

        if($values['count'] >= $campaign['shoppingCount']){

            // Payment Modal
            print('
                <div id="doreaPaymentModalContainer" class="dorea-payment-modal-container">
                        <lable>please write your wallet adddress:</lable>
                        <input style="" id="doreaModalText" type="text" name="dorea-modal-text">
                        <button onclick="doreapay()" id="dorea_claimCashback" type="button">claim cashback</button>
                </div>
            ');
            break;
        }
    }

    print (' <button onclick="doreapay()" id="dorea_claimCashback" type="button">claim cashback</button>');

    $contractAddress = get_option('dorea_contract_address');

    print('
        <script>
        function doreapay(){

        /*
        // issue is here
            return (async() => {
                 
                 let doreaPaymentModalButton = document.getElementById("dorea_claimCashback");
                 if (window.ethereum) {
                                      
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
                                          
                                           
                                           const userBalance = await window.ethereum.request({
                                                 method: "eth_getBalance",
                                                params: [userAddress, "latest"]
                                           });
                                            
                           
                                
                                        //const provider = new ethers.JsonRpcProvider("https://polygon-amoy.g.alchemy.com/v2/LuZ5CnAEURDtdQRwm9VJlkHRQR29Kw_a");
                                        //const provider = new ethers.JsonRpcProvider("http://127.0.0.1:8545");
                                        const provider = new BrowserProvider(window.ethereum);
                            
                                        // Get the signer from the provider metamask
                                        const signer = await provider.getSigner();
                           
                                        const contract = new ethers.Contract("'.$contractAddress.'", abi, signer);
                            
                                        const tx = await contract.show();
                                        //const amount = await contract.get();
                                        //console.log(amount)
                                        //const tx = await contract.pay([""],"2000000000000000000");
                                        console.log(tx)
                                        
                                       // window.location.replace("/wordpress/wp-admin/admin.php?page=credit");
                                  }
                           
                    };
                    
                    xhr.send();
                    
                               
                            
                                }
                 
             }
          */   
        }
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
    echo json_encode($responseArray);

}