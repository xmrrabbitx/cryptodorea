<?php

/**
 * Claim Campaign Modal
 */

use Cryptodorea\Woocryptodorea\utilities\compile;

add_action('wp', 'claimModal');
function claimModal()
{

    $compile = new compile();
    $abi = $compile->abi();

    $campaignUser = get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login);

    static $sumUserEthers;
    static $totalPurchases;
    static $doreaContractAddress;
    static $qualifiedWalletAddresses;

    if($campaignUser) {
        foreach ($campaignUser as $campaignName => $campaignValue) {

            $doreaContractAddress = get_option($campaignName . '_contract_address');

            $cashbackInfo = get_transient($campaignName) ?? null;
            $shoppingCount = $cashbackInfo['shoppingCount'];
            $cryptoAmount = $cashbackInfo['cryptoAmount'];
            $ethBasePrice = 0.0004;

            if ($campaignValue['purchaseCounts'] >= $shoppingCount) {

                // calculate final price in ETH format
                $qualifiedPurchases = array_chunk($campaignValue['total'], $cashbackInfo['shoppingCount']);
                $result = [];
                array_map(function ($value) use ($shoppingCount, &$result) {
                    if (count($value) == $shoppingCount) {
                        $value = array_sum($value);
                        // calculate percentage of each value
                        $result[] = $value;
                    }
                }, $qualifiedPurchases);

                $totalPurchases += count($result) * $shoppingCount;
                $qualifiedPurchasesTotal = array_sum($result);
                $userEther = number_format(((($qualifiedPurchasesTotal * $cryptoAmount) / 100) * $ethBasePrice), 10);
                $sumUserEthers[] = $userEther;

                $qualifiedWalletAddresses[] = $campaignValue['walletAddress'];

            }
        }

        $sumUserEthers = json_encode($sumUserEthers) ?? "null";
        $qualifiedWalletAddresses = json_encode($qualifiedWalletAddresses) ?? "null";
var_dump($qualifiedWalletAddresses);
        return print ('
               <script type="module">
               
                    // load etherJs library
                    import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js";
                    
                    let doreaClaim = document.getElementById("doreaClaim");
                    doreaClaim.addEventListener("click", async function (){
                           
                        let amounts = '.$sumUserEthers.';
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
                        
                        await window.ethereum.request({ method: "eth_requestAccounts" });
                        const accounts = await ethereum.request({ method: "eth_accounts" });
                        const account = accounts[0];
                        
                        const provider = new BrowserProvider(window.ethereum);
                            
                        const signer = await provider.getSigner();
                  
                        let message = "Dorea Cashback: you are claiming your cashback now!";
                        
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
                        
                        console.log(messageHash)
                        console.log(r)
                        console.log(s)
                        console.log(v)
                  
                        let cryptoAmountBigInt = [];
                        for(const amount of amounts){
                        
                            if((typeof(amount) === "number") && (Number.isInteger(amount))){
                              
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
                        console.log(cryptoAmountBigInt)
                        if('.$sumUserEthers.' !== null){
                            try{
                                let contractAddress = '.$doreaContractAddress.';
                                const contract = new ethers.Contract(contractAddress, '.$abi.',signer)
                 
                                 await contract.pay(
                                    '.$qualifiedWalletAddresses.',
                                    cryptoAmountBigInt, 
                                    messageHash, 
                                    v, 
                                    r, 
                                    s,
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
                                                       
                                           
                                      }
                                });
                            });
                            }catch (error) {
                                console.log(error)
                            }
                        }
                    })     
               </script>
               <!-- claim campaign modal -->
               <div class="!grid !grid-cols-1 !mt-5">
                    <button class="campaignPayment_ !p-3 !w-64 !bg-[#faca43] !rounded-md !mx-auto" id="doreaClaim">Claim Reward</button>
               </div>
        ');
    }
}

