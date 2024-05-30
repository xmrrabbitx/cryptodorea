<?php

/**
 * Crypto Cashback Campaign Credit
 */

use Cryptodorea\Woocryptodorea\controllers\web3\smartContractController;
use Web3\Contract;
use Web3\Eth;
use Web3\Providers\HttpProvider;
use Web3\Web3;
use Web3\Utils;


function dorea_cashback_campaign_credit()
{

    //var_dump(dechex(1337));
    //die('stopppp!!!');

    print("campaign credit page");
    //var_dump(get_transient('dorea 7'));

    if (isset($_GET['campaignName'])) {

        $campaignName = $_GET['campaignName'];
        print("CHARGE CAMPAIN NAME" . $campaignName);

        print("
            <form method='POST' action='" . esc_url(admin_url('admin-post.php')) . "' id='campaign_credit'>
                <input type='hidden' name='action' value='campaign_credit_charge'>
                <input type='hidden' name='campaignName' value= '" . $campaignName . "' >
                <input type='text' name='amount'>
                <button type='submit'>charge</button>
            </form>
        ");
    }

    print('
                
        <input id="creditAmount" type="text">
        <button id="metamask" style="display:none">Fund your Campaign</button>
        <button id="metamaskDisconnect" style="display:none">Disconnect Metamask</button>



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

                  if(window.ethereum._state.accounts.length > 0){
                     
                      document.getElementById("metamask").style.display = "none";
                      document.getElementById("metamaskDisconnect").style.display = "block";
                     
                       
                  }else {
                      document.getElementById("metamask").style.display = "block";
                      document.getElementById("metamaskDisconnect").style.display = "none";
                  }
                  
                    document.getElementById("metamask").addEventListener("click", async () => {
                             
                            let contractAmount = document.getElementById("creditAmount").value;
                            
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
                        
                                   // const privateKey = "0x37483b8eebc0281371d439a846b6114f2e6cda020d92453b89285306a099ff88"; // Replace with the private key of the account from Ganache
                                   // const signer = new ethers.Wallet(privateKey, provider);
                       
                                    const factory = new ContractFactory(abi, bytecode, signer);
                                    
                                     //If your contract requires constructor args, you can specify them here
                                    const contract = await factory.deploy({
                                          value: BigInt(contractAmount / 0.000000000000000001).toString(),
                                          gasLimit :3000000
                                    });
                                   
                        
                                    //const contract = new ethers.Contract("", abi, signer);
                        
                                    //const tx = await contract.show();
                                    //const amount = await contract.get();
                                    //console.log(amount)
                                    //const tx = await contract.pay([""],"2000000000000000000");
                                    //console.log(tx)
                                    
                                   // window.location.replace("/wordpress/wp-admin/admin.php?page=credit");
                              }
                       
                };
                
                xhr.send();
                
                           
                        
                            }


});
          
              })();
          }
          



        </script>
    
    ');



}

/**
 * Campaign Credit
 */
add_action('admin_post_campaign_credit_charge', 'dorea_admin_campaign_credit_charge');

function dorea_admin_campaign_credit_charge()
{
    if(isset($_POST['campaignName'])) {

        $campaignName = $_POST['campaignName'];

        $doreaWeb3 = new smartContractController();
        $doreaWeb3->getAmount($_POST['amount'], $campaignName);
    }
}


add_action('admin_menu', 'dorea_admin_campaign_smart_contract');
function dorea_admin_campaign_smart_contract()
{
/*
    $contractInfo = json_decode(file_get_contents('php://input', true));

    if (isset($contractInfo)) {
         $doreaWeb3 = new smartContractController();
         $compiledConract = $doreaWeb3->compile();

         if($compiledConract){
             $doreaWeb3->deployContract($contractInfo, $compiledConract);
         }

    }
*/
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