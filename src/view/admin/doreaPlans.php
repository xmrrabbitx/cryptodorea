<?php

/**
 * Crypto Cashback Plans
 */

function doreaPlans()
{
    print ("plans page");


    print("<head>Monthly</head>");

    print("<head>6 Months</head>");

    print("<head>Yearly</head>");

    print ('

        <button class="doreaBuy"  value="19">buy</button>
        <button class="doreaBuy"  value="29">buy</button>
        <button class="doreaBuy"  value="49">buy</button>

    ');

    print('<script type="module">
         import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js";

            let doreaPaymentModalButton = document.querySelectorAll(".doreaBuy");
            console.log(doreaPaymentModalButton)
             doreaPaymentModalButton.forEach(
                
                (element) =>             
           
                    element.addEventListener("click", function(){
                      // Request access to Metamask
                     setTimeout(delay, 1000)
                     function delay(){
                         (async () => {
                      
                                 let amount =  element.value;
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
                                                    if(parseInt(userBalance) < 300000000000000){
                                                        
                                                   
                                                        
                                                    }else{
                                                      
                                                    }
                                                    
                                   
                                                    const provider = new BrowserProvider(window.ethereum);
                                        
                                                    // Get the signer from the provider metamask
                                                    const signer = await provider.getSigner();
                                        
                                                    const contract = new ethers.Contract("0xFF0Ea865f6f93933500FCCB251d78F0FCE52eE2A", abi, signer);
                                       
                                                    // value:"4000000000000000000"
                                                    await contract.pay({
                                                        value:"4000000000000000000"
                                                    });
                                                    
                                                
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