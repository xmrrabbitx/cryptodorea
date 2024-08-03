<?php

use Cryptodorea\Woocryptodorea\utilities\compile;
use Cryptodorea\Woocryptodorea\controllers\paymentController;


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
       
         campaignNames.forEach(
                
            (element) =>             
           
              element.addEventListener("click", async function(){
                
                let elmentIed = element.id;
                const contractAddress = elmentIed.split("_")[2];
                let campaignName = elmentIed.split("_")[1];

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

                let re = await contract.pay('.$walletsList.',"2000000000000000000", 1,1,messageHash, v, r, s);
               
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


    $userList = get_option("dorea_campaigns_users");

    foreach ($userList as $users){

        $campaigns = get_option("dorea_campaigninfo_user_" . $users);
        foreach ($campaigns as $campaignInfo){

            print($users . "<br>");


        }

    }


    $campaignName = $_GET['cashbackName'];

    $payment = new paymentController();
    $walletsList = $payment->walletslist($campaignName);

    $doreaContractAddress = get_option($campaignName . '_contract_address');

    print('<button class="campaignPayment_" id="campaignPayment_' . $campaignName . '_' . $doreaContractAddress . '">pay</button>');
    dorea_campaign_pay($walletsList);
}