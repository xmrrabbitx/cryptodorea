<?php

/**
 * the payment modal for admin campaigns
 */
//('admin_menu', 'dorea_campaign_pay');
function dorea_campaign_pay(): void
{
    print('<script>

         let campaignNames = document.querySelectorAll(".campaignPayment_");
       
         campaignNames.forEach(
                
            (element) =>             
           
              element.addEventListener("click", async function(){
                     
                await window.ethereum.request({ method: "eth_requestAccounts" });
                const accounts = await ethereum.request({ method: "eth_accounts" });
                const account = accounts[0];
                  
              
                try{
                  let txHash = await window.ethereum.request({
                      "method": "eth_sendTransaction",
                      "params": [
                        {
                          "to": "0x6E94C86c6417677F4de93700681184c5f49F55BD",
                          "from": account,
                          "gas": "0x2DC6C0",
                          "value": "0x8ac7230489e80000",
                          "data": "0x",
                          "gasPrice": "0x4a817c800"
                        }
                      ]
                   });
                 console.log("Transaction sent with hash:", txHash);
                } catch (error) {
                    console.error(error);
                }
               
               
            })
         )
                   
    </script>');
}