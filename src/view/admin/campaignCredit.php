<?php

use Cryptodorea\DoreaCashback\controllers\campaignCreditController;
use Cryptodorea\DoreaCashback\utilities\encrypt;

/**
 * Crypto Cashback Campaign Credit
 * @throws Exception
 */
function dorea_cashback_campaign_credit():void
{

    // load campaign credit Style
    wp_enqueue_style('DOREA_CAMPAIGNCREDIT_STYLE',plugins_url('/cryptodorea/css/campaignCredit.css'));

    if(!empty($_GET['cashbackName'])) {

        $campaignName = $_GET['cashbackName'];

        $doreaContractAddress = get_option($campaignName . '_contract_address');
        if($doreaContractAddress){
            wp_redirect('admin.php?page=crypto-dorea-cashback');
        }

        // load campaign credit scripts
        wp_enqueue_script('DOREA_CAMPAIGNCREDIT_SCRIPT',plugins_url('/cryptodorea/js/campaignCredit.js'), array('jquery', 'jquery-ui-core'));

        // set  enc value for deployment
        $params = array('campaignName'=>$campaignName);
        wp_localize_script( 'DOREA_CAMPAIGNCREDIT_SCRIPT', 'OBJECT', $params );

    }else{
        wp_redirect('admin.php?page=crypto-dorea-cashback');
    }

    print('
        <main>
            <h1 class="p-5 text-sm font-bold">Fund Campaign</h1> </br>
            
             <p id="errorMessg" style="display: none"></p>
            
            <div class="container mx-auto pl-5 pt-2 pb-5 shadow-transparent text-center rounded-md">
              
              <h2 class="!text-center !text-lg !divide-y !mt-5">Crypto Dorea Cashback</h2>
              <hr class="border-1 !w-64 !text-center !dark:bg-gray-700 !w-48 1h-1 !mx-auto !mt-2">
              
              <div class="!grid !grid-cols-1 !justify-items-center">
              
                <div class="!col-span-1 !w-64 !mt-10">
                    <span class="">
                        <label class="!text-pretty !text-left !float-left">Notes: Ethers must be in the Ether format e.g: 0.0004</label>
                    </span>
                    <span class="">
                        <input class="!rounded-md !w-64 !mt-5 !p-2 !focus:ring-green-500 !border-hidden !bg-white" id="creditAmount" type="text" placeholder="Insert Ethers">
                    </span>
                </div>
                <div class="!col-span-1 !w-12/12 !mt-5">
                 <button class="!p-3 !w-64 !bg-[#faca43] !rounded-md" id="doreaFund" style="">Fund your Campaign</button>
                </div>
               
                <button  id="metamaskDisconnect" style="display:none">Disconnect Metamask</button>
        
                <p class="!mt-10" id="dorea_metamask_error" style="display:none;color:#ff5d5d;"></p>
                <p class="!mt-10" id="dorea_fund_error" style="display:none;color:#ff5d5d;"></p>
                
              </div>
            </div>
           
        </main>
    ');


}

/**
 * Campaign Credit smart contract address
 */
add_action('admin_post_dorea_contract_address', 'dorea_contract_address');

function dorea_contract_address()
{

    static $doreaContractAddress;

    if(isset($_GET['cashbackName'])) {
        $campaignName = $_GET['cashbackName'];
        $doreaContractAddress = get_option($campaignName . '_contract_address') ?? null;
    }

    // get Json Data
    $json_data = file_get_contents('php://input');
    $json = json_decode($json_data);

    if($doreaContractAddress){
        // update contract adddress of specific campaign
        update_option($campaignName . '_contract_address', $json->contractAddress);
    }else{
        // set contract adddress into option
        add_option($campaignName . '_contract_address', $json->contractAddress);
    }

    $contractAmount = $json->contractAmount;
    if($contractAmount){

        $campaignInfo = get_transient($campaignName);
        $campaignInfo['contractAmount'] = $contractAmount;
        set_transient($campaignName, $campaignInfo);

    }

}

