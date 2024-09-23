<?php

use Cryptodorea\Woocryptodorea\utilities\compile;
use Cryptodorea\Woocryptodorea\controllers\paymentController;
use Cryptodorea\Woocryptodorea\controllers\usersController;
use Cryptodorea\Woocryptodorea\controllers\expireCampaignController;


/**
 * the payment modal for admin campaigns
 */
function dorea_campaign_pay($qualifiedWalletAddresses=null, $cryptoAmount=null, $qualifiedUserEthers=null, $remainingAmount=null, $usersList=null, $totalPurchases=null): void
{

    $compile = new compile();
    $abi = $compile->abi();
    //$bytecode = $compile->bytecode();

    if($qualifiedWalletAddresses && $cryptoAmount && $qualifiedUserEthers) {

        $qualifiedWalletAddresses = json_encode($qualifiedWalletAddresses);
        $sumAmount = array_sum($qualifiedUserEthers);
        $sumUserEthers = json_encode($qualifiedUserEthers);
        $usersList = json_encode($usersList);
        $qualifiedUserEthers = json_encode($qualifiedUserEthers);
        $totalPurchases = json_encode($totalPurchases);

    }else{

        $sumAmount = "null";
        $sumUserEthers = "null";
        $qualifiedWalletAddresses = "null";
        $usersList = 'null';
        $qualifiedUserEthers = 'null';
        $totalPurchases = 'null';
    }

    print('<script type="module">
          
         // load etherJs library
         import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js";
         
         
         let campaignNames = document.querySelectorAll(".campaignPayment_");
         const metamaskError = document.getElementById("dorea_metamask_error");
                            
         campaignNames.forEach(
                
            (element) =>             
           
              element.addEventListener("click", async function(){
                
                  
                function convertToWei(amount){
               
                    if( (typeof(amount) === "number") && (Number.isInteger(amount))){
                          
                            const creditAmountBigInt = BigInt(amount);
                            const multiplier = BigInt(1e18);
                            return creditAmountBigInt * multiplier;
                           
                    }
                    else{
                         
                            const creditAmount = amount; // This is a floating-point number
                            const multiplier = BigInt(1e18); // This is a BigInt
                            const factor = 1e18; 
                                                    
                            // Convert the floating-point number to an integer
                            const creditAmountInt  = BigInt(Math.round(creditAmount * factor));
                            return creditAmountInt * multiplier / BigInt(factor);
                            
                    }
                } 
                
                function convertWeiToEther(amount){
               
                    const creditAmountBigInt = amount;
                    const multiplier = 1e18;
                    return creditAmountBigInt / multiplier;
                           
                } 
                  
                let elmentIed = element.id;
                const contractAddress = elmentIed.split("_")[3];
                let campaignNameFirst = elmentIed.split("_")[1];
                let campaignNameSecond = elmentIed.split("_")[2];
                let campaignName = campaignNameFirst + "_" + campaignNameSecond;
                let paymentStatus = elmentIed.split("_")[4];
                
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
                
                return false
                // convert ether to wei
                let sumAmount = '.$sumAmount.';
                let sumWei = null;
                let cryptoAmountBigInt = [];
                
                if(sumAmount !== null){
                
                    let cryptoAmount = ' . $sumUserEthers . ';
                   
                    for(const amount of  cryptoAmount){
                      
                        if( (typeof(amount) === "number") && (Number.isInteger(amount))){
                          
                            const creditAmountBigInt = BigInt(amount);
                            const multiplier = BigInt(1e18);
                            cryptoAmountBigInt.push(creditAmountBigInt * multiplier);
                           
                        }
                        else{
               
                            const creditAmount = amount; // This is a floating-point number
                            const multiplier = BigInt(1e18); // This is a BigInt
                            const factor = 1e18; 
                                                    
                            // Convert the floating-point number to an integer
                            const creditAmountInt  = BigInt(Math.round(creditAmount * factor));
                            cryptoAmountBigInt.push(creditAmountInt * multiplier / BigInt(factor));
                            
                        }
                    }
                   
                   sumWei = convertToWei(sumAmount);

                }

                try{
                 
                    const contract = new ethers.Contract(contractAddress, '.$abi.',signer)
                 
                    // check if transaction exceeds the contract balance
                    const balance = await contract.getBalance();
                 
                    if(sumWei > balance){
                        
                        metamaskError.style.display = "block";
                        const errorText = document.createTextNode("Sorry, the campaign has not enough fund!");
                        metamaskError.appendChild(errorText);
                        return false;
                        
                    }
              
                    if(paymentStatus === "pay"){
                            await contract.pay(
                                '.$qualifiedWalletAddresses.',
                                cryptoAmountBigInt, 
                                messageHash, 
                                v, 
                                r, 
                                s
                            ).then(async function(response){
                                response.wait().then(async (receipt) => {
                                      // transaction on confirmed and mined
                                      if (receipt) {
                                      
                                           let succMessage = "payment has been successfull!";
                                           Toastify({
                                                  text: succMessage,
                                                  duration: 3000,
                                                  style: {
                                                    background: "linear-gradient(to right, #32DC98, #2EC4A1)",
                                                  },
                                           }).showToast();
                                           
                                           await new Promise(r => setTimeout(r, 1500));
                                           let balance = await contract.getBalance();
                                           balance = convertWeiToEther(parseInt(balance));
                                    
                                           // get contract address
                                           let xhr = new XMLHttpRequest();
                                                    
                                           // remove wordpress prefix on production
                                           xhr.open("POST", "/wordpress/wp-admin/admin-post.php?action=dorea_new_contractBalance", true);
                                           xhr.onreadystatechange = async function() {
                                              if (xhr.readyState === 4 && xhr.status === 200) {
                                           
                                                  window.location.reload();        
                                              }
                                           }
                                                        
                                           xhr.send(JSON.stringify({"balance":JSON.stringify(balance),"campaignName":campaignName, "usersList":  '.$usersList.' , "amount":'.$qualifiedUserEthers.' , "totalPurchases":'.$totalPurchases.' }));      
                                      }
                                });
                            });
                            
                       }else if(paymentStatus === "fund"){
                       
                          let fundAgainAmount = convertToWei('.$remainingAmount.');
                          
                               await contract.fundAgain(
                                    messageHash, 
                                    v, 
                                    r, 
                                    s,
                                    {              
                                        value: fundAgainAmount.toString(),
                                        gasLimit :3000000,                
                                    },
                               ).then(async function(response){
                                  response.wait().then(async (receipt) => {
                                      // transaction on confirmed and mined
                                      if (receipt) {
                                           let succMessage = "payment has been successfull!";
                                           Toastify({
                                                  text: succMessage,
                                                  duration: 3000,
                                                  style: {
                                                    background: "linear-gradient(to right, #32DC98, #2EC4A1)",
                                                  },
                                           }).showToast();
                                           
                                           await new Promise(r => setTimeout(r, 1500));
                                           
                                           let balance = await contract.getBalance();
                                           balance = convertWeiToEther(parseInt(balance));
                                    
                                           // get contract address
                                           let xhr = new XMLHttpRequest();
                                                    
                                           // remove wordpress prefix on production
                                           xhr.open("POST", "/wordpress/wp-admin/admin-post.php?action=dorea_new_contractBalance", true);
                                           xhr.onreadystatechange = async function() {
                                              if (xhr.readyState === 4 && xhr.status === 200) {
                                            
                                                  window.location.reload();        
                                              }
                                           }
                                                        
                                           xhr.send(JSON.stringify({"balance":JSON.stringify(balance),"campaignName":campaignName}));
                                             
                                      }
                                  });
                                  
                                })
                       } 
                    
                }catch (error) {
                       if(typeof error.revert === "undefined")   {
                           Toastify({
                               text: "Something went wrong. please try again!",
                               duration: 3000,
                               style: {
                                    background: "linear-gradient(to right, #FF4E41, #E22D2D)",
                               },
                           }).showToast();
                       }else{
                           let errorMessg = error.revert.args[0];
                           if(errorMessg === "Insufficient balance"){
                               errorMessg = "Insufficient balance";
                               Toastify({
                                      text: "Insufficient balance in Campaign",
                                      duration: 3000,
                                      style: {
                                         background: "linear-gradient(to right, #FF4E41, #E22D2D)",
                                      },
                               }).showToast();
                           }else if(errorMessg === "User is not Authorized!!!"){
                                errorMessg = "You dont have permission to pay!";
                                Toastify({
                                      text: "Insufficient balance in Campaign",
                                      duration: 3000,
                                      style: {
                                         background: "linear-gradient(to right, #FF4E41, #E22D2D)",
                                      },
                               }).showToast();
                           }else{
                               errorMessg = "payment was not successfull! please try again!";
                               Toastify({
                                      text: errorMessg,
                                      duration: 3000,
                                      style: {
                                        background: "linear-gradient(to right, #FF4E41, #E22D2D)",
                                      },
                               }).showToast();
                           }
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

    print ("<script>let counter = document.getElementById('counter');</script>");
    $userList = get_option("dorea_campaigns_users_dorea_9b46841");

    //var_dump(count($userList));
    /*
    //die;
    // set 1 mil records
    for ($i=0;$i<=170000;$i++){

        $users = base64_encode(random_bytes(8));
        $userList[] = $users;
        update_option("dorea_campaigns_users_dorea_9b46841",$userList);
        add_option('dorea_campaigninfo_user_' . $users, [base64_encode(random_bytes(8))]);

    }

    die;
    //var_dump($userList);
    //var_dump(get_option('dorea_campaigninfo_user_' . 'Vl44+zx5l6U='));
    // remove 1 mil records
    /*
    foreach ($userList as $users){

        delete_option('dorea_campaigninfo_user_' . $users);

        print("<script>
             counter.innerHTML ='$i';
        </script>");
    }
    array_splice($userList,1,1000000);
    update_option("dorea_campaigns_users_dorea_9b46841",$userList);
*/


    static $home_url = 'admin.php?page=crypto-dorea-cashback';
    static $qualifiedUserEthers;
    static $qualifiedWalletAddresses;

    $cashbackName = $_GET['cashbackName'];
    $cashbackInfo = get_transient($cashbackName) ?? null;
    
    // redirect to admin page if no campaign existed!
    if(!$cashbackInfo){
        wp_redirect($home_url);
    }

    $expireDate = get_transient($cashbackName)['timestampExpire'];

    $cryptoAmount = $cashbackInfo['cryptoAmount'];

    $expire = new expireCampaignController();

    $userList = get_option("dorea_campaigns_users_" . $cashbackName);

    print('
         <!-- load tailwinf library -->
        <script src="https://cdn.tailwindcss.com"></script>
    ');

    print('

         <!-- load poppins font -->
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
    }
    else{

        $totalEthers = [];
        $usersList = [];

        $contractAmount = $cashbackInfo['contractAmount'];
        $shoppingCount = $cashbackInfo['shoppingCount'];

        $addtoPaymentSection = true;
        $paymentButtonsSection = true;
        $paidUserSection= true;

        $i = 0;
        $cache = [];
        foreach ($userList as $users) {

            $sumUserEthers = [];
            $campaignUser = get_option('dorea_campaigninfo_user_' . $users);

            //hypothetical price of eth _ get this from an online service
            $ethBasePrice = 0.0004;

            if($campaignUser) {

                if (isset($campaignUser[$cashbackName]['order_ids']) && $campaignUser[$cashbackName]['purchaseCounts'] >= $shoppingCount) {

                    if ($addtoPaymentSection) {
                        print('
                                <div class="!grid !grid-cols-1 !ml-5 !w-3/3 !mr-5 !mt-3 !p-10 !gap-3 !text-left !rounded-xl  !bg-white !shadow-sm !border">
                                    <div class="!col-span-1 !grid !grid-cols-5">
                                         <span class="!text-center !pl-3">
                                            Username
                                            <hr>
                                         </span>
                                         <span class="!text-center">
                                             Wallet Address
                                             <hr>
                                         </span>
                                         <span class="!text-center">
                                            Purchase Counts
                                            <hr>
                                         </span>
                                         <span class="!text-center">
                                             Amount
                                             <hr>
                                         </span>
                                          <span class="!text-center">
                                             Eligibility to Pay
                                             <hr>
                                         </span>
                                    </div>
                            ');
                        $addtoPaymentSection = false;
                    }

                    // calculate final price in ETH format
                    $qualifiedPurchases = array_chunk($campaignUser[$cashbackName]['total'], $shoppingCount);
                    $result = [];
                    array_map(function ($value) use ($shoppingCount, &$result) {
                        if (count($value) == $shoppingCount) {
                            $value = array_sum($value);
                            // calculate percentage of each value
                            $result[] = $value;
                        }
                    }, $qualifiedPurchases);

                    $totalPurchases[] = count($result) * $shoppingCount;
                    $qualifiedPurchasesTotal = array_sum($result);

                    print("<div class='!col-span-1 !grid !grid-cols-5 !pt-3 !text-center'>");
                    print("<span class='!pl-3 !col-span-1'>" . $users . "</span> ");
                    print("<span class='!pl-3 !col-span-1'>" . substr($campaignUser[$cashbackName]['walletAddress'], 0, 4) . "****" . substr($campaignUser[$cashbackName]['walletAddress'], 36, 6) . "</span>");
                    print("<span class='!pl-3 !col-span-1'>" . $campaignUser[$cashbackName]['purchaseCounts'] . "</span>");
                    print("<span class='!pl-3 !col-span-1'>$" . array_sum($campaignUser[$cashbackName]['total']) . "</span>");


                    $userEther = number_format(((($qualifiedPurchasesTotal * $cryptoAmount) / 100) * $ethBasePrice), 10);

                    $totalEthers[] = $userEther;

                    print ("<span class='!pl-3 !pt-1 !col-span-1 !mx-auto'>");

                    $sumUserEthers[] = $userEther;

                    if (array_sum($totalEthers) <= $contractAmount && array_sum($sumUserEthers) <= $contractAmount) {

                        // set qualified users to pay
                        $qualifiedUserEthers[] = $userEther;
                        $qualifiedWalletAddresses[] = $campaignUser[$cashbackName]['walletAddress'];
                        $usersList[] = $users;

                        print("
                                   <svg class='size-5 text-green-500' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                                        <path fill-rule='evenodd' d='M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z' clip-rule='evenodd' />
                                   </svg>
                             ");

                    } else {

                        print("
                                 <svg class='size-5 text-amber-500' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                                      <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                                 </svg>
                             ");

                    }

                    print ("
                           </span>
                        </div>
                    ");
                }


                if ($users === end($userList)) {

                    //print("</div>");
                    // get contract address of campaign
                    $doreaContractAddress = get_option($cashbackName . '_contract_address');
                    if (!empty($totalEthers)) {

                        // check expiration of campaign
                        if ($expire->check($expireDate)) {

                            // check for funding campaign
                            if ((float)array_sum($totalEthers) > (float)$contractAmount) {

                                print("
                                <!-- Fund Again -->
                                <div class='!mx-auto !text-center !mt-5'>
                                    <a href='#' class='campaignPayment_ !p-3 !w-64 !bg-[#faca43] !rounded-md' id='campaignPayment_" . $cashbackName . "_" . $doreaContractAddress . "_fund" . "'>Fund Again</a>
                                </div>
                            ");

                                if ($qualifiedWalletAddresses) {
                                    print("
                                    <p class='!text-center !mt-5 !text-slate-500'>Or</p>
                                    <!-- Pay Anyway -->
                                    <div class='!grid !grid-cols-1 !mt-5'>
                                        <button class='campaignPayment_ !p-3 !w-64 !bg-[#faca43] !rounded-md !mx-auto' id='campaignPayment_" . $cashbackName . "_" . $doreaContractAddress . "_pay" . "'>Pay Anyway</button>
                                    </div>
                                ");
                                }

                            } else {
                                print('
                                <!-- Pay All -->
                                <div class="!grid !grid-cols-1 !mt-5">
                                    <button class="campaignPayment_ !p-3 !w-64 !bg-[#faca43] !rounded-md !mx-auto" id="campaignPayment_' . $cashbackName . '_' . $doreaContractAddress . '_pay' . '">Pay All</button>
                                </div>
                            ');
                            }

                            // calculate remaining amount eth to pay
                            $remainingAmount = (float)$contractAmount - array_sum($totalEthers);
                            $remainingAmount *= -1;

                            // trigger payment js modal
                            dorea_campaign_pay($qualifiedWalletAddresses, $cryptoAmount, $qualifiedUserEthers, $remainingAmount, $usersList, $totalPurchases);

                            print('<p id="dorea_metamask_error" style="display:none;color:#ff5d5d;"></p>');
                        } else {
                            print('
                            <!-- Not Ready to Pay -->
                            <div class="!grid !grid-cols-1 !mt-5">
                                <p class="campaignPayment_ !p-3 !w-64 !bg-[#faca43] !rounded-md !mx-auto !text-center">Not Ready to Pay</p>
                            </div>
                        ');
                        }

                        print('
                       <!-- End Campaign -->
                       <div class="!grid !grid-cols-1 !mt-5">
                            <p class="!p-3 !w-64 !bg-[#faca43] !rounded-md !mx-auto !text-center">campaign is finished!</p>
                       </div>
                    ');
                    } else {
                        print ("
                        <!-- error on no users -->
                        <div class='!text-center !text-sm !mx-auto !w-96 !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
                             <svg class='size-6 text-rose-400' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                                 <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                            </svg>
                            <p class='!pt-3 !pb-2'>
                              there is no users eligible to pay in the loyalty campaign!
                            </p>
                           
                        </div>
                    ");
                    }

                }


                // list claimed rewards
                if (isset($campaignUser[$cashbackName]['claimedReward'])) {
                    print('
                            <div class="!grid !grid-cols-1 !ml-5 !w-3/3 !mr-5 !mt-3 !p-10 !gap-3 !text-left !rounded-xl  !bg-white !shadow-sm !border">
                                <div class="!col-span-1 !grid !grid-cols-5">
                                     <span class="!text-center !pl-3">
                                         Username
                                         <hr>
                                     </span>
                                     <span class="!text-center">
                                         Claimed Rewards
                                         <hr>
                                     </span>
                            </div>
                        ');

                    print("<div class='!col-span-1 !grid !grid-cols-5 !pt-3 !text-center'>");
                    print("<span class='!pl-3 !col-span-1 !text-sm'>" . $users . "</span> ");
                    print("<span class='!pl-3 !col-span-1 !text-sm'>" . $campaignUser[$cashbackName]['claimedReward'] . " ETH</span>");

                    print("
                                </div>
                            </div>
                        ");
                }


            }


        }


    }


    print("    
            </div>
        </main>
    ");
}

/**
 * get the new contract balance
 */
add_action('admin_post_dorea_new_contractBalance', 'dorea_new_contractBalance');
function dorea_new_contractBalance():void
{

    // get Json Data
    $json_data = file_get_contents('php://input');
    $json = json_decode($json_data);

    if ($json) {
        $campaignInfoUser = get_transient($json->campaignName);
        $campaignInfoUser['contractAmount'] = $json->balance;

        $amount = $json->amount;
        $totalPurchases = $json->totalPurchases;

        set_transient($json->campaignName, $campaignInfoUser);

        if (isset($json->usersList)){
            $usersList = $json->usersList;
            $users = new usersController();
            $users->is_paid($json->campaignName,$usersList,$amount,$totalPurchases);
        }
    }
}