<?php

use Cryptodorea\Woocryptodorea\utilities\compile;

/**
 * Crypto Cashback Campaign Credit
 */
function dorea_cashback_campaign_credit()
{

    $compile = new compile();
    $abi = $compile->abi();
    $bytecode = $compile->bytecode();

    $campaignName = null;
    if(isset($_GET['cashbackName'])) {

        $campaignName = $_GET['cashbackName'];

        $encryptedSecretHash = "0x" . get_transient($campaignName)['secretHash'];
        $encryptedInitKey = "0x" . get_transient($campaignName)['initKey'];

        $doreaContractAddress = get_option($campaignName . '_contract_address');
        if($doreaContractAddress){
            wp_redirect('admin.php?page=crypto-dorea-cashback');
        }

    }else{
       wp_redirect('admin.php?page=crypto-dorea-cashback');
    }

    print('

        <h1 class="p-5 text-sm font-bold">Fund Campaign</h1> </br>
        
        <div class="container mx-auto pl-5 pt-2 pb-5 shadow-transparent text-center rounded-md">
          
          <h2 class="!text-center !text-lg !divide-y !mt-5">Crypto Dorea Cashback</h2>
          <hr class="border-1 !w-64 !text-center !dark:bg-gray-700 !w-48 1h-1 !mx-auto !mt-2">
          
          <div class="!grid !grid-cols-1 !justify-items-center">
          
            <div class="!col-span-1 !w-64 !mt-10">
                <span class="">
                    <label class="!text-pretty !text-left !float-left">Notes: Ethers must be in the Ether format e.g: 0.0004</label>
                </span>
                <span class="">
                    <input class="!rounded-md !w-64 !mt-5 !p-2 !focus:ring-green-500 !border-hidden !bg-white" id="creditAmount" type="text" placeholder="Insert Ethers">
                </span>
            </div>
            <div class="!col-span-1 !w-12/12 !mt-5">
             <button class="!p-3 !w-64 !bg-[#faca43] !rounded-md" id="doreaFund" style="">Fund your Campaign</button>
            </div>
           
            <button  id="metamaskDisconnect" style="display:none">Disconnect Metamask</button>
    
            <p class="" id="dorea_metamask_error" style="display:none;color:#ff5d5d;"></p>
            <p class="" id="dorea_fund_error" style="display:none;color:#ff5d5d;"></p>
            
          </div>
        </div>
        
        
        <script>          
             // Request access to Metamask
             setTimeout(delay, 1000)
             function delay(){
                 (async () => {
           
                      if(window.ethereum._state.accounts.length > 0){
                            document.getElementById("metamaskDisconnect").addEventListener("click", async () => {
                         
                                // disconnect user 
                                const result = await window.ethereum.request({
                                method: "wallet_revokePermissions",
                                params: [{
                                  eth_accounts: {}
                                }]
                              });
                              
                              // remove wordpress prefix on production
                              window.location.replace("/wordpress/wp-admin/admin.php?page=credit");
                              
                            })
                      }
                 })();
             }
        </script>
        
        <script type="module">
             import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js";
    
             // Request access to Metamask
             setTimeout(delay, 1000)
             function delay(){
                 (async () => {
    
                    document.getElementById("doreaFund").addEventListener("click", async () => {
    
                        let contractAmount = document.getElementById("creditAmount").value;
                        const metamaskError = document.getElementById("dorea_metamask_error");
                       
                        if(contractAmount === ""){
                            metamaskError.style.display = "block";
                            metamaskError.innerHTML = "cryptocurrency amount could not be left empty!";
                            return false;
                        }
                        else if(!Number.isInteger(parseInt(contractAmount))){
                                    
                           metamaskError.style.display = "block";
                           metamaskError.innerHTML = "cryptocurrency amount must be in the decimal format!";
                           return false;
                                    
                        }
                        else{
                            metamaskError.style.display = "none";
                        }
                               
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
                                      
                                      /*
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
                                      
                                       */
                                      
                                       
                                    const accounts = await window.ethereum.request({method: "eth_requestAccounts"});
                                    const userAddress = accounts[0];
                                          
                                           
                                    const userBalance = await window.ethereum.request({
                                                 method: "eth_getBalance",
                                                params: [userAddress, "latest"]
                                    });
    
                                    // check balance of metamask wallet 
                                    if(parseInt(userBalance) < 300000000000000){
                                                
                                                metamaskError.style.display = "block";
                                                metamaskError.innerHTML =  "not enough balance to support fee. please fund your wallet at least 0.0003 ETH!";
                                                return false;
                                                
                                            }
                                    else{
                                                metamaskError.style.display = "none";
                                            }
                                          
                                  try{
                                    
                                        const provider = new BrowserProvider(window.ethereum);
                            
                                        // Get the signer from the provider metamask
                                        const signer = await provider.getSigner();
                                
                                        const factory = new ContractFactory(' .$abi.', "'.$bytecode. '", signer)
                                         
                                        let contractAmountBigInt;
                                        if( (typeof(contractAmount) === "number") && (Number.isInteger(contractAmount))){
                                            const creditAmountBigInt = BigInt(contractAmount);
                                            const multiplier = BigInt(1e18);
                                            contractAmountBigInt= creditAmountBigInt * multiplier;
                                          
                                        }else{
                                        
                                            const creditAmount = contractAmount; // This is a floating-point number
                                            const multiplier = BigInt(1e18); // This is a BigInt
                                            const factor = 1e18; 
                                            
                                            // Convert the floating-point number to an integer
                                            const creditAmountInt  = BigInt(Math.round(creditAmount * factor));
                                            contractAmountBigInt= creditAmountInt * multiplier / BigInt(factor);
                                  
                                        }
                                        
                                        //If your contract requires constructor args, you can specify them here
                                        const contract = await factory.deploy(
                                            {
                                                      
                                              value: contractAmountBigInt.toString(),
                                              gasLimit :3000000,
                                                      
                                            }
                                        ).then(function(transaction) {
                                                    let contractAddress = transaction.target;
                                                    
                                                    // get contract address
                                                    let xhr = new XMLHttpRequest();
                                            
                                                    // remove wordpress prefix on production
                                                    xhr.open("POST", "/wordpress/wp-admin/admin-post.php?action=dorea_contract_address&cashbackName=' . $campaignName . '", true);
                                                    xhr.onreadystatechange = async function() {
                                                        if (xhr.readyState === 4 && xhr.status === 200) {
                                                            
                                                            // remove wordpress prefix on production 
                                                            window.location.replace("/wordpress/wp-admin/admin.php?page=credit");
                                                        
                                                        }
                                                    }
                                                
                                                    xhr.send(JSON.stringify({"contractAddress":contractAddress}));
                                                    
                                                    });
                                    
                                  }catch (error) {
                                        
                                       console.log(error.revert.args[0])
                                       const fundError = document.getElementById("dorea_fund_error");
                                       // show error popup message
                                       fundError.style.display = "block";
                                       fundError.innerHTML = "Funding the Contract was not successfull! please try again";
                                       return false;
                                           
                                  }
                                        
                            }
                    })
    
                 })();
             }
        </script>
    ');


}


/**
 * Campaign Credit Loyalty ABI and Bytecode of smart contract
 */
add_action('admin_post_loyalty_json_file', 'dorea_admin_loyalty_json_file');

function dorea_admin_loyalty_json_file()
{

    $loyaltyJson = file_get_contents(WP_PLUGIN_DIR . '/woo-cryptodorea/loyalty.json');
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
 * Campaign Credit smart contract address
 */
add_action('admin_post_dorea_contract_address', 'dorea_contract_address');

function dorea_contract_address()
{

    if(isset($_GET['cashbackName'])) {
        $campaignName = $_GET['cashbackName'];

        $doreaContractAddress = get_option($campaignName . '_contract_address') ?? null;

    }

    // get Json Data
    $json_data = file_get_contents('php://input');
    $json = json_decode($json_data);

    if($doreaContractAddress){
        // update contract adddress of specific campaign
        update_option($campaignName . '_contract_address', $json->contractAddress);
    }else{
        // set contract adddress into option
        add_option($campaignName . '_contract_address', $json->contractAddress);
    }


}

