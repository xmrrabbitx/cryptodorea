<?php


namespace Cryptodorea\Woocryptodorea\view\modals\claimCampaign;

/**
 * Claim Campaign Modal
 */

use Cryptodorea\Woocryptodorea\utilities\compile;
use Cryptodorea\Woocryptodorea\utilities\encrypt;

/**
 * a modal to claim cashback
 */
//add_action('wp', 'claimModal');
/**
 * @throws Exception
 */
function claimModal()
{
    $compile = new compile();
    $abi = $compile->abi();

    $campaignUser = get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login);

    static $sumUserEthers;
    static $totalPurchases;
    static $doreaContractAddress;
    static $qualifiedWalletAddresses;
    static $_encValue;
    static $_encMessage;
    static $userEther;

    if($campaignUser) {
//var_dump($campaignUser);
        foreach ($campaignUser as $campaignName => $campaignValue) {

            $doreaContractAddress = get_option($campaignName . '_contract_address');

            $cashbackInfo = get_transient($campaignName) ?? null;
            $shoppingCount = $cashbackInfo['shoppingCount'];
            $cryptoAmount = $cashbackInfo['cryptoAmount'];
            $ethBasePrice = 0.0004;

            if ($campaignValue['purchaseCounts'] >= $shoppingCount) {
//var_dump($doreaContractAddress);
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
                //$sumUserEthers[] = $userEther;

                //$qualifiedWalletAddresses[] = $campaignValue['walletAddress'];

            }
            if ($userEther) {
//var_dump($userEther);
                $amountsBinary = '';
                //foreach ($sumUserEthers as $amount) {
                $wei = bcmul($userEther, "1000000000000000000", 0);
//var_dump($wei);

                // Convert the decimal value to a 32-byte (256-bit) padded hex string
                $hexAmount = str_pad(gmp_strval(gmp_init($wei, 10), 16), 64, '0', STR_PAD_LEFT);
                $amountsBinary .= hex2bin($hexAmount); // Convert hex string to binary
                //}

                $sumUserEthers = json_encode($sumUserEthers) ?? "null";
                $qualifiedWalletAddresses = json_encode($qualifiedWalletAddresses) ?? "null";

                $encryptionInfo = get_option('encryptionCampaign');
                $encryptionInfo = $encryptionInfo[$campaignName];
                if ($encryptionInfo) {
                    // generate key-value encryption
                    $encrypt = new encrypt();
                    $encryptGeneration = $encrypt->encryptGenerate();
                    $encryptionMessage = $encrypt->keccak(hex2bin($encryptionInfo['key']), $encryptGeneration['value'], $amountsBinary);

                    $_encValue = '0x' . bin2hex($encryptGeneration['value']);
                    $_encMessage = $encryptionMessage;
var_dump(($encryptionInfo));
//var_dump($encrypt->keccak(hex2bin($encryptionInfo['key']), hex2bin('f3280ff83d50feba0a23378bddad8340'), $amountsBinary));
//var_dump($_encValue);
//var_dump($_encMessage);
                }
                print('            
               <!-- claim campaign modal -->
               <div class="!grid !grid-cols-1 !mt-5">
                    <button value=' . $doreaContractAddress . "_" . $campaignValue['walletAddress'] . "_" . $wei . "_" . $_encValue . "_" . $_encMessage . ' class="campaignPayment_ doreaClaim !p-3 !w-64 !bg-[#faca43] !rounded-md !mx-auto">Claim Reward</button>
               </div>
            ');
            }


//var_dump($sumUserEthers);
            //delete_option('encryptionCampaign');
            //var_dump($encryptionInfo);
            //var_dump($hexAmount);
            //var_dump(hex2bin($encryptionInfo['key']));
            //var_dump('0x' . Keccak::hash(hex2bin($encryptionInfo['key']) . hex2bin('fa033cd30d2eedc174fd2571c7251a4d') . $amountsBinary, 256));
            //var_dump($_encValue);
            //var_dump($_encMessage);

//die;
            print ('

               <!-- load toastify library -->
               <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
               <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

               <script type="module">
            
                    // load etherJs library
                    import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js";
                     
                    let deleteCampaignModal = document.querySelectorAll(".doreaClaim");

                    deleteCampaignModal.forEach(
                
                      (element) =>             
               
                        element.addEventListener("click", async function(){
                            
                            let contractAddress = element.value.split("_")[0] ?? null;
                            let walletAddress = element.value.split("_")[1] ?? null;
                            let amount =  element.value.split("_")[2] ?? null;
                            let _encValue = element.value.split("_")[3] ?? null;
                            let _encMessage = element.value.split("_")[4] ?? null;
                         
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
                            
                            if (window.ethereum) {
                                        
                                const userAddress = accounts[0];
                                                  
                                const userBalance = await window.ethereum.request({
                                      method: "eth_getBalance",
                                      params: [userAddress, "latest"]
                                });
            
                                // check balance of metamask wallet 
                                if(parseInt(userBalance) < 300000000000000){
                                              
                                    
                                    let err = "not enough balance to support fee! \n please fund your wallet at least 0.0003 ETH!";
                                    Toastify({
                                       text: err,
                                       duration: 3000,
                                       style: {
                                             background: "#ff5d5d",
                                       },
                                    }).showToast();
                                    return false;          
                                }
                            
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
                                
                                /*
                                let cryptoAmountBigInt = [];
                                for(const amount of amounts){
                                
                                    if((typeof(amount) === "number") && (Number.isInteger(amount))){
                                      
                                        const creditAmountBigInt = BigInt(amount);
                                        const multiplier = BigInt(1e18);
                                        cryptoAmountBigInt.push((creditAmountBigInt * multiplier).toString());
                                       
                                    }
                                    else{
                           
                                        const creditAmount = amount; // This is a floating-point number
                                        const multiplier = BigInt(1e18); // This is a BigInt
                                        const factor = 1e18; 
                                                                
                                        // Convert the floating-point number to an integer
                                        const creditAmountInt  = BigInt(Math.round(creditAmount * factor));
                                        cryptoAmountBigInt.push((creditAmountInt * multiplier / BigInt(factor)).toString());
                                    }
                                    
                                }
                                */
                                //console.log(parseInt(amount))
                                console.log(_encValue)
                                //console.log(_encMessage.toString())
                                if(amount !== null){
                                    try{
                                       
                                        const contract = new ethers.Contract(contractAddress, ' . $abi . ',signer);
                                        
                                         await contract.pay(
                                            walletAddress,
                                            parseInt(amount), 
                                            _encValue.toString(),
                                            _encMessage.toString(),
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
                                                   xhr.open("POST", "/wordpress/wp-admin/admin-post.php?action=dorea_claimed_cashback", true);
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
                                        // reload on any error
                                        // get contract address
                                        let xhr = new XMLHttpRequest();
                                                            
                                        // remove wordpress prefix on production
                                        xhr.open("POST", "/wordpress/wp-admin/admin-post.php?action=dorea_claimed_cashback", true);
                                        xhr.onreadystatechange = async function() {
                                        if (xhr.readyState === 4 && xhr.status === 200) {
                                                   
                                             // window.location.reload();        
                                           }
                                        }
                                        xhr.send(JSON.stringify({"test":"testing!"}));
                                        
                                    }
                                }
                                
                            }
                        }) 
                    )    
               </script>
   
            ');
        }
    }
}


/**
 * check if cashback claimed by user!
 */
add_action('admin_post_dorea_claimed_cashback', 'dorea_claimed_cashback');
function dorea_claimed_cashback()
{
    // get Json Data
    $json_data = file_get_contents('php://input');
    $json = json_decode($json_data);
    var_dump($json);
    var_dump(get_option('nextEncryptionCampaign'));

    die;
}