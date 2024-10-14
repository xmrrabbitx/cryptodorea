<?php

namespace Cryptodorea\Woocryptodorea\view\modals\claimCampaign;

use Couchbase\ValueRecorder;
use Cryptodorea\Woocryptodorea\controllers\usersController;
use Cryptodorea\Woocryptodorea\utilities\encrypt;

/**
 * a modal to claim cashback
 * @throws Exception
 */
function claimModal():void
{

    // check if cashback claimed or not
    $json_data = file_get_contents('php://input');
    $json = json_decode($json_data);

    if($json) {

        // check encryption: balance + encval + key
        //$campaignUser = get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login);
        $campaignInfo = get_transient($json->campaignName);

        // convert wei to ether
        //$balance = bcdiv($json->balance, "1000000000000000000", 18);
        $campaignInfo['contractAmount'] = $json->balance;
        set_transient($json->campaignName, $campaignInfo);

        $encryptionInfo = get_option('encryptionCampaign_' . wp_get_current_user()->user_login);
        $encryptionInfo = $encryptionInfo[$json->campaignName];

        $amountsBinary = '';
        $hexAmount = str_pad(gmp_strval(gmp_init($json->amountWei, 10), 16), 64, '0', STR_PAD_LEFT);
        $amountsBinary .= hex2bin($hexAmount); // Convert hex string to binary

        $encrypt = new encrypt();
        $encryptionMessage = $encrypt->keccak(hex2bin($encryptionInfo['key']), hex2bin(substr($json->_encValue,2)), $amountsBinary);

        if($encryptionMessage === $encryptionInfo['encryptedMessage']){

            // check is_paid
            $users = new usersController();
            $users->is_claimed($json->campaignName, $json->claimedAmount, $json->totalPurchases);

        }
    }else{

        // load claim campaign style
        wp_enqueue_style('DOREA_CLAIMCAMPAIGN_STYLE', plugins_url('/woo-cryptodorea/css/claimCampaign.css'));

        $campaignUser = get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login);

        static $sumUserEthers;
        static $totalPurchases;
        static $doreaContractAddress;
        static $qualifiedWalletAddresses;
        static $_encValue;
        static $_encMessage;
        static $userEther;
        static $campaignEnd;

        if ($campaignUser) {

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
                    //$sumUserEthers[] = $userEther;

                    //$qualifiedWalletAddresses[] = $campaignValue['walletAddress'];

                }
                if ($userEther) {

                    if ($cashbackInfo['contractAmount'] >= $userEther) {

                        $campaignEnd = true;

                        $amountsBinary = '';
                        $wei = bcmul($userEther, "1000000000000000000", 0);


                        // Convert the decimal value to a 32-byte (256-bit) padded hex string
                        $hexAmount = str_pad(gmp_strval(gmp_init($wei, 10), 16), 64, '0', STR_PAD_LEFT);
                        $amountsBinary .= hex2bin($hexAmount); // Convert hex string to binary

                        $sumUserEthers = json_encode($sumUserEthers) ?? "null";
                        $qualifiedWalletAddresses = json_encode($qualifiedWalletAddresses) ?? "null";

                        $encryptionInfoCampaigns = get_option('encryptionCampaign');
                        $encryptionInfo = $encryptionInfoCampaigns[$campaignName];
                        $userencryption = [];
                        if ($encryptionInfo) {
                            // generate key-value encryption
                            $encrypt = new encrypt();
                            $encryptGeneration = $encrypt->encryptGenerate();
                            $encryptionMessage = $encrypt->keccak(hex2bin($encryptionInfo['key']), $encryptGeneration['value'], $amountsBinary);

                            $_encValue = '0x' . bin2hex($encryptGeneration['value']);
                            $_encMessage = $encryptionMessage;

                            $userencryption[$campaignName]['key'] = $encryptionInfo['key'];
                            $userencryption[$campaignName]['value'] = substr($_encValue, 2);
                            $userencryption[$campaignName]['encryptedMessage'] = $_encMessage;

                            get_option('encryptionCampaign_' . wp_get_current_user()->user_login) !== false ? update_option('encryptionCampaign_' . wp_get_current_user()->user_login, $userencryption) : add_option('encryptionCampaign_' . wp_get_current_user()->user_login, $userencryption);


                        }

                        print('
                           <div id="doreaModalContent">
                               <!-- claim campaign modal -->
                               <div class="doreaModalContent !grid !grid-cols-1 !mt-3">
                                    <p class="!mt-0 !mb-0"> ' . substr($campaignValue['walletAddress'], 0, 4) . "****" . substr($campaignValue['walletAddress'], 30, 12) . '</p>
                                    <button value=' . $doreaContractAddress . "_" . $campaignValue['walletAddress'] . "_" . $wei . "_" . $_encValue . "_" . $_encMessage . "_" . $campaignName . "_" . $userEther . "_" . $totalPurchases . ' class="doreaClaim !p-3 !w-64 !bg-[#faca43] !rounded-md !mx-auto">Claim Reward</button>
                               </div>
                           </div>
                        ');
                    }
                }
            }

            if ($userEther && $campaignEnd) {
                print('
                    <div id="doreaClaimModal" class="!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-7 !rounded-md !text-center !border">            
                       <span id="doreaCloseModal">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 !text-rose-400 !cursor-pointer !hover:text-rose-200 !float-right">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                           </svg>
                       </span>  
                       <p id="doreaClaimError"></p>    
                       <p id="doreaClaimSuccess"></p>    
                       <h5 class="bold">Congratulations</h5>
                       <h6 class="">Claim Your Cashback 🎉</h6>
                               
                    </div>
                ');
            }

            // load claim campaign scripts
            wp_enqueue_script('DOREA_CLAIMCAMPAIGN_SCRIPT', plugins_url('/woo-cryptodorea/js/claimCampaign.js'), array('jquery', 'jquery-ui-core'));

        }

    }
}

