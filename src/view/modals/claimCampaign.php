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

            // load claim campaign scripts
            wp_enqueue_script('DOREA_CLAIMCAMPAIGN_SCRIPT',plugins_url('/woo-cryptodorea/js/claimCampaign.js'), array('jquery', 'jquery-ui-core'));


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