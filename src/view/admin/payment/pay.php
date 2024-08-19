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
                console.log(r,s,v)        
                console.log(messageHash)        
                const contract = new ethers.Contract(contractAddress, '.$abi.',signer)
                try{
                    const balance = await contract.getBalance();
                    console.log(balance)
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
    $cashbackInfo = get_transient($cashbackName);

    $expire = new expireCampaignController();

    $userList = get_option("dorea_campaigns_users_" . $cashbackName);

    // load tailwind cdn
    print('<script src="https://cdn.tailwindcss.com"></script>');

    print('
        <style>
            body{
                background: #f6f6f6;
            }
        </style>
    ');

    print("
            <div class='!container !pl-5 !pt-2 !pb-5 !shadow-transparent  !rounded-md'>
    ");
    print("<h1 class='!p-5 !text-sm !font-bold'>Payment</h1> </br>");
    print("<h2 class='!pl-5 !text-sm !font-bold'>Get Paid in Ethers</h2> </br>");

    if(empty($userList)){
        print ("
            <div class='!text-center !text-sm !mx-auto !w-64 !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
                 <svg class='size-6 text-rose-400' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                     <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                </svg>
                <p class='!pt-3 !pb-2'>
                  there is no users participant into the loyalty campaign!
                </p>
               
            </div>
        ");
    }else {
        print('
           <div class="!flex !grid-flex !pl-5 !w-64 !mr-5 !mt-3 !pl-3 !p-10 !gap-3 !text-center !rounded-xl  !bg-white !shadow-sm !border">
        ');
        foreach ($userList as $users) {

            $campaignInfoUsers = get_option('dorea_campaigninfo_user_' . $users);

            $campaigns = get_option("dorea_campaigninfo_user_" . $users);

            if($campaigns ) {
                foreach ($campaigns as $campaignInfo) {
                    if (in_array($cashbackName, $campaignInfo['campaignNames'])){
                        print("<span>".$users."</span> ");

                            print($campaignInfo['walletAddress'] . "</br>");

                    }
                }
            }

        }

        print("</div>");


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

    print("    
        </div>
    ");
}