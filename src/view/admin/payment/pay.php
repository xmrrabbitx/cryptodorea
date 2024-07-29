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
                
                    // get abi and bytecode
                    let xhr = new XMLHttpRequest();
                   // remove wordpress prefix on production
                   xhr.open("GET", "/wordpress/wp-admin/admin-post.php?action=loyalty_json_file", true);
                   xhr.onreadystatechange = async function() {
                   if (xhr.readyState === 4 && xhr.status === 200) {
                       let response = JSON.parse(xhr.responseText);
                       let abi = response[0]
                       let bytecode = response[1]
                                      
                                           
                  
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
                console.log({ signature });
            
                // split signature
                const r = signature.slice(0, 66);
                const s = "0x" + signature.slice(66, 130);
                const v = parseInt(signature.slice(130, 132), 16);
                        
               
                //const contract = new ethers.Contract("0xD0F35A89b65Aa1DC3B79a2d54C49f42Dd69e7F5C",abi, signer)
                const contract = new ethers.Contract("0x742C8E0800a53dF477173f11cf3bEaF729C304E2", '.$abi.',signer);

                let re = await contract.pay(["0x445D816fbc80c270509018c7C5286B8d193A4187","0x096bc12574B265647f1441A3c01fBdA5F21cC47f"],"2000000000000000000", 1,1,messageHash, v, r, s);
                console.log(re)
                
                
                }
                   }
                    xhr.send();
                
                
            })
         )
                   
    </script>');
}

/*
 * const contract = new ethers.Contract("", '.$abi.', "'.$bytecode.'")
 * const contract = new ethers.Contract("", '.$abi.', signer)

                console.log(ethers.verifyMessage(message, sig))

const contract = new ethers.Contract(
                    "0xD0F35A89b65Aa1DC3B79a2d54C49f42Dd69e7F5C",

                    , "'.$bytecode.'"
                )


 console.log(messageHash)
                let sig = await signer.signMessage(messageHash);

[0x5B38Da6a701c568545dCfcB03FcB875f56beddC4,0x4B20993Bc481177ec7E8f571ceCaE8A9e22C02db]

["0x5B38Da6a701c568545dCfcB03FcB875f56beddC4","0x4B20993Bc481177ec7E8f571ceCaE8A9e22C02db"]



 */