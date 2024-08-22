<?php

use Cryptodorea\Woocryptodorea\utilities\compile;
use Cryptodorea\Woocryptodorea\controllers\paymentController;
use Cryptodorea\Woocryptodorea\controllers\expireCampaignController;


/**
 * the payment modal for admin campaigns
 */
function dorea_campaign_pay($walletsList, $cryptoAmount, $shoppingCount): void
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
                
                // convert ether to wei
                let cryptoAmount = '.$cryptoAmount.';
                let cryptoAmountBigInt;
                if( (typeof(cryptoAmount) === "number") && (Number.isInteger(cryptoAmount))){
                    
                    const creditAmountBigInt = BigInt(cryptoAmount);
                    const multiplier = BigInt(1e18);
                    cryptoAmountBigInt = creditAmountBigInt * multiplier;
                                          
                }else{
                                        
                    const creditAmount = cryptoAmount; // This is a floating-point number
                    const multiplier = BigInt(1e18); // This is a BigInt
                    const factor = 1e18; 
                                            
                    // Convert the floating-point number to an integer
                    const creditAmountInt  = BigInt(Math.round(creditAmount * factor));
                    cryptoAmountBigInt= creditAmountInt * multiplier / BigInt(factor);
                                  
                }
  
                const contract = new ethers.Contract(contractAddress, '.$abi.',signer)
                 
                try{
                   const balance = await contract.getBalance();
                   // issue here
                   let qualifiedWalletsCounts = parseInt(balance / cryptoAmountBigInt); //parseInt((balance / BigInt('.$walletsList.'.length)) / (balance / BigInt('.$walletsList.'.length)));
               
                    console.log(qualifiedWalletsCounts)
                    
                    if(balance !== 0n){
                        
                        let re = await contract.pay(' . $walletsList . '.slice(0,qualifiedWalletsCounts), cryptoAmountBigInt.toString(), messageHash, v, r, s);
                       
                    }else{              
                        // show error popup message
                        metamaskError.style.display = "block";
                        const errorText = document.createTextNode("Sorry, this campaign fund reached to the end!");
                        metamaskError.appendChild(errorText);
                        return false;
                    } 
                }catch (error) {
                    console.log(error)
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

    $cryptoAmount = $cashbackInfo['cryptoAmount'];
    $shoppingCount = $cashbackInfo['shoppingCount'];

    $expire = new expireCampaignController();

    $userList = get_option("dorea_campaigns_users_" . $cashbackName);

    // load tailwind cdn
    print('<script src="https://cdn.tailwindcss.com"></script>');

    print('
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        <style>
            body{
                background: #f6f6f6;
            }
            main{
                font-family: "Poppins", sans-serif !important;
            }
        </style>
    ');

    print("<main>");
    print("
            <div class='!container !pl-5 !pt-2 !pb-5 !shadow-transparent  !rounded-md'>
    ");
    print("<h1 class='!p-5 !text-sm !font-bold'>Payment</h1> </br>");
    print("<h2 class='!pl-5 !text-sm !font-bold'>Get Paid in Ethers</h2> </br>");

    if(empty($userList)){
        print ("
            <!-- error on no users -->
            <div class='!text-center !text-sm !mx-auto !w-96 !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
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
           <div class="!grid !grid-cols-1 !ml-5 !w-3/3 !mr-5 !mt-3 !p-10 !gap-3 !text-left !rounded-xl  !bg-white !shadow-sm !border">
           <div class="!col-span-1 !grid !grid-cols-3">
            <span class="!text-center !pl-3">
                Username
            </span>
            <span class="!text-center">
                Wallet Address
            </span>
            <span class="!text-center">
                Purchase Counts
            </span>
           </div>
        ');
        foreach ($userList as $users) {

            $campaignInfoUsers = get_option('dorea_campaigninfo_user_' . $users);

            $campaigns = get_option("dorea_campaigninfo_user_" . $users);
//delete_option("dorea_campaigninfo_user_" . $users);

            if($campaigns ) {
                foreach ($campaigns as $campaignInfo) {
                    //var_dump($campaignInfo);
                    //var_dump($campaignInfo['campaignNames']);
                    if(isset($campaignInfo['order_ids'])) {
                        if (in_array($cashbackName, $campaignInfo['campaignNames'])) {
                            print("<div class='!col-span-1 !grid !grid-cols-3 !text-center'>");
                            print("<span class='!pl-3 !col-span-1'>" . $users . "</span> ");
                            print("<span class='!pl-3 !col-span-1'>" . substr($campaignInfo['walletAddress'], 0, 4) . "****" . substr($campaignInfo['walletAddress'], 28, 34) . "</span>");
                            print("<span class='!pl-3 !col-span-1'>" . $campaignInfo['purchaseCounts'][$cashbackName] . "</span>");
                            print("</div>");
                        }
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
            print('
                <div class="!grid !grid-cols-1 !mt-5">
                    <button class="campaignPayment_ !p-3 !w-64 !bg-[#faca43] !rounded-md !mx-auto" id="campaignPayment_' . $campaignName . '_' . $doreaContractAddress . '">pay</button>
                </div>
            ');

            // payment js modal
            dorea_campaign_pay($walletsList, $cryptoAmount, $shoppingCount);

            print('<p id="dorea_metamask_error" style="display:none;color:#ff5d5d;"></p>');
        }else{
            die("not ready for payment!");
        }
    }

    print("    
            </div>
        </main>
    ");
}