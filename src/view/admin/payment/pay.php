<?php

use Cryptodorea\Woocryptodorea\utilities\compile;

/**
 * the payment modal for admin campaigns
 */
//('admin_menu', 'dorea_campaign_pay');
function dorea_campaign_pay(): void
{

    //$doreaContractAddress = get_option($campaignName . '_contract_address');

    $compile = new compile();
    $abi = $compile->abi();
    $bytecode = $compile->bytecode();

    print('<script type="module">

         import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js";

         let campaignNames = document.querySelectorAll(".campaignPayment_");
       
         campaignNames.forEach(
                
            (element) =>             
           
              element.addEventListener("click", async function(){
                     console.log(element.value)
                await window.ethereum.request({ method: "eth_requestAccounts" });
                const accounts = await ethereum.request({ method: "eth_accounts" });
                const account = accounts[0];
                
                const provider = new BrowserProvider(window.ethereum);
                            
                const signer = await provider.getSigner();
                let message = "sign into ethers.org?";
                
                let sig = await signer.signMessage(message);
                
                const contract = new ethers.Contract("0x29E560Ce695A65fcBC8845c33a94B7F895642542", '.$abi.', "'.$bytecode.'");
                           
                console.log(ethers.verifyMessage(message, sig))
               
            })
         )
                   
    </script>');
}