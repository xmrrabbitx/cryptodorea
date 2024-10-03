<?php

use Cryptodorea\Woocryptodorea\controllers\campaignCreditController;
use Cryptodorea\Woocryptodorea\utilities\compile;
use Cryptodorea\Woocryptodorea\utilities\encrypt;

/**
 * Crypto Cashback Campaign Credit
 * @throws Exception
 */
function dorea_cashback_campaign_credit():void
{

    if(!empty($_GET['cashbackName'])) {

        $campaignName = $_GET['cashbackName'];

        // compile abd && bytecode
        $compile = new compile();
        $abi = $compile->abi();
        $bytecode = $compile->bytecode();

        // generate key-value encryption
        $encrypt = new encrypt();
        $encryptGeneration = $encrypt->encryptGenerate();
        $encryptionMessage = $encrypt->keccak($encryptGeneration['key'], $encryptGeneration['value']);

        $campaigCredit = new campaignCreditController();
        $campaigCredit->encryptionGeneration($campaignName, $encryptGeneration['key'],$encryptGeneration['value'], $encryptionMessage);

        $_encKey = '0x' . bin2hex($encryptGeneration['key']);

        $doreaContractAddress = get_option($campaignName . '_contract_address');
        if($doreaContractAddress){
            wp_redirect('admin.php?page=crypto-dorea-cashback');
        }

    }else{
        wp_redirect('admin.php?page=crypto-dorea-cashback');
    }


    print('
        <style>
            body{
                background: #f6f6f6;
            }
            main{
                font-family: "Poppins", sans-serif !important;
            }
        </style>
    ');

    print('
        <main>
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
        
                <p class="!mt-10" id="dorea_metamask_error" style="display:none;color:#ff5d5d;"></p>
                <p class="!mt-10" id="dorea_fund_error" style="display:none;color:#ff5d5d;"></p>
                
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
                           
                            document.getElementById("doreaFund").disabled = true;
                             
                            let contractAmount = document.getElementById("creditAmount").value;
                            const metamaskError = document.getElementById("dorea_metamask_error");
                           
                            if(contractAmount === ""){
                                metamaskError.style.display = "block";
                                let err = "cryptocurrency amount could not be left empty!";
                                Toastify({
                                   text: err,
                                   duration: 3000,
                                   style: {
                                         background: "#ff5d5d",
                                   },
                               }).showToast();
                               document.getElementById("doreaFund").disabled = false;
                               return false;
                            }
                            else if(!Number.isInteger(parseInt(contractAmount))){
                                        
                               metamaskError.style.display = "block";
                               let err = "cryptocurrency amount must be in the decimal format!";
                               Toastify({
                                   text: err,
                                   duration: 3000,
                                   style: {
                                         background: "#ff5d5d",
                                   },
                               }).showToast();
                               document.getElementById("doreaFund").disabled = false;
                               return false;
                                        
                            }
                            else{
                                metamaskError.style.display = "none";
                            }
                                   
                            if (window.ethereum) {
                                       
                                      const accounts = await window.ethereum.request({method: "eth_requestAccounts"});
                                      const userAddress = accounts[0];
                                              
                                      const userBalance = await window.ethereum.request({
                                              method: "eth_getBalance",
                                              params: [userAddress, "latest"]
                                      });
        
                                        // check balance of metamask wallet 
                                      if(parseInt(userBalance) < 300000000000000){
                                          
                                                    document.getElementById("doreaFund").disabled = false;
                                                    let err = "not enough balance to support fee! \n please fund your wallet at least 0.0003 ETH!";
                                                    Toastify({
                                                          text: err,
                                                          duration: 3000,
                                                          style: {
                                                            background: "#ff5d5d",
                                                          },
                                                    }).showToast();
                                                    return false;
                                                    
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
                                            await factory.deploy(
                                            "'.$_encKey.'",
                                                {       
                                                  value: contractAmountBigInt.toString(),
                                                  gasLimit :3000000,
                                                          
                                                }
                                            ).then(async function(response) {
                                            
                                                let contractAddress = response.target;
                                                
                                                // wait for deployment
                                                response.waitForDeployment().then(async (receipt) => {
                                       
                                                    if(receipt){
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
                                                    
                                                        xhr.send(JSON.stringify({"contractAddress":contractAddress,"contractAmount":contractAmount}));
                                                    }
                                                });  
                                                
                                            });
                                        
                                      }catch (error) {
                                           document.getElementById("doreaFund").disabled = false;
                                           let err = "Funding the Contract was not successfull! please try again";
                                           Toastify({
                                                  text: err,
                                                  duration: 3000,
                                                  style: {
                                                    background: "#ff5d5d",
                                                  },
                                           }).showToast();
                                           return false;
                                               
                                      }
                            }
                               
                             document.getElementById("doreaFund").disabled = false;
                        })
                        
                     })();
                 }
            </script>
        
        </main>
    ');


}

/**
 * Campaign Credit smart contract address
 */
add_action('admin_post_dorea_contract_address', 'dorea_contract_address');

function dorea_contract_address()
{

    static $doreaContractAddress;
    static $contractAmount;
    static $campaignName;

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

    $contractAmount = $json->contractAmount;
    if($contractAmount){

        $campaignInfo = get_transient($campaignName);
        $campaignInfo['contractAmount'] = $contractAmount;
        set_transient($campaignName, $campaignInfo);

    }

}

