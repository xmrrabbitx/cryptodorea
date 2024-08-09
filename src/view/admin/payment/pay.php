<?php

use Cryptodorea\Woocryptodorea\utilities\compile;
use Cryptodorea\Woocryptodorea\controllers\paymentController;
use Cryptodorea\Woocryptodorea\controllers\expireCampaignController;


/**
 * the payment modal for admin campaigns
 */
//('admin_menu', 'dorea_campaign_pay');
function dorea_campaign_pay($walletsList): void
{

    $compile = new compile();
    $abi = $compile->abi();
    //$bytecode = $compile->bytecode();

    $walletsList = json_encode($walletsList);

    print('<script type="module">

         import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js";

         let campaignNames = document.querySelectorAll(".campaignPayment_");
         const metamaskError = document.getElementById("dorea_metamask_error");
                            
         campaignNames.forEach(
                
            (element) =>             
           
              element.addEventListener("click", async function(){
                
                let elmentIed = element.id;
                const contractAddress = elmentIed.split("_")[3];
                let campaignName = elmentIed.split("_")[1];

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
                
                
                await window.ethereum.request({ method: "eth_requestAccounts" });
                const accounts = await ethereum.request({ method: "eth_accounts" });
                const account = accounts[0];
          
                const provider = new BrowserProvider(window.ethereum);
                            
                const signer = await provider.getSigner();
          
                let message = "hello";
                
                const messageHash = ethers.id(message);
               
                // sign hashed message
                const signature = await ethereum.request({
                  method: "personal_sign",
                  params: [messageHash, accounts[0]],
                });
            
                // split signature
                const r = signature.slice(0, 66);
                const s = "0x" + signature.slice(66, 130);
                const v = parseInt(signature.slice(130, 132), 16);
                        
                const contract = new ethers.Contract(contractAddress, '.$abi.',signer)
                try{
                    const balance = await contract.getBalance();
           
                    if(balance !== 0n){
                        
                            let re = await contract.pay(' . $walletsList . ',"2000000000000000000", 1,1,messageHash, v, r, s);
                       
                    }else{              
                        // show error popup message
                        metamaskError.style.display = "block";
                        const errorText = document.createTextNode("Sorry, this campaign fund reached to the end!");
                        metamaskError.appendChild(errorText);
                        return false;
                    } 
                }catch (error) {
                      //"User is not Authorized!!!"
                       let errorMessg = error.revert.args[0];
                       if(errorMessg === "Insufficient balance"){
                           errorMessg = "Insufficient balance";
                       }else if(errorMessg === "User is not Authorized!!!"){
                           errorMessg = "Insufficient balance";
                       }else{
                           errorMessg = "payment was  not successfull! please try again!";
                       }
                       // show error popup message
                       metamaskError.style.display = "block";
                       metamaskError.innerHTML = errorMessg;
                       return false;
                    }
            })
         )
                   
    </script>');
}


/**
 * Campaign payment list wallet address users
 */
add_action('admin_post_pay_campaign', 'dorea_admin_pay_campaign');

function dorea_admin_pay_campaign()
{

    $cashbackName = $_GET['cashbackName'];
    $expireDate = get_transient($cashbackName)['timestamp'];

    $expire = new expireCampaignController();

    $userList = get_option("dorea_campaigns_users_" . $cashbackName);

    if(empty($userList)){
        print ("there is no users participant into the loyalty campaign!");
    }else {
        foreach ($userList as $users) {

            $campaigns = get_option("dorea_campaigninfo_user_" . $users);
            if($campaigns) {
                print("<span>".$users."</span> ");

                foreach ($campaigns as $campaignInfo) {

                    print($campaignInfo['walletAddress'] . "</br>");

                }
            }

        }


        $campaignName = $_GET['cashbackName'];

        $payment = new paymentController();
        $walletsList = $payment->walletslist($campaignName);

        $doreaContractAddress = get_option($campaignName . '_contract_address');

        if($expire->check($expireDate)){
            print('<button class="campaignPayment_" id="campaignPayment_' . $campaignName . '_' . $doreaContractAddress . '">pay</button>');
            dorea_campaign_pay($walletsList);
            print('<p id="dorea_metamask_error" style="display:none;color:#ff5d5d;"></p>');
        }else{
            die("not ready for payment!");
        }
    }
}