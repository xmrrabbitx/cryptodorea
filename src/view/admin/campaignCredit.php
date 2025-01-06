<?php

/**
 * Crypto Cashback Campaign Credit
 * @throws Exception
 */
function dorea_cashback_campaign_credit():void
{
    // update admin footer
    function add_admin_footer_text() {
        return 'Crypto Dorea: <a class="!underline" href="https://cryptodorea.io">cryptodorea.io</a>';
    }
    add_filter( 'admin_footer_text', 'add_admin_footer_text', 11 );
    function update_admin_footer_text() {
        return 'Version 1.0.0';
    }
    add_filter( 'update_footer', 'update_admin_footer_text', 11 );

    // load campaign credit Style
    wp_enqueue_style('DOREA_CAMPAIGNCREDIT_STYLE',plugins_url('/cryptodorea/css/campaignCredit.css'));

    if(!empty($_GET['cashbackName'])) {

        $campaignName = sanitize_key($_GET['cashbackName']);

        $doreaContractAddress = get_option($campaignName . '_contract_address');
        if($doreaContractAddress){
            wp_redirect('admin.php?page=crypto-dorea-cashback');
        }

        // load campaign credit scripts
        wp_enqueue_script('DOREA_CAMPAIGNCREDIT_SCRIPT',plugins_url('/cryptodorea/js/campaignCredit.js'), array('jquery', 'jquery-ui-core'));

        // set  enc value for deployment
        $params = array('campaignName'=>$campaignName);
        wp_localize_script( 'DOREA_CAMPAIGNCREDIT_SCRIPT', 'params', $params );

    }else{
        wp_redirect('admin.php?page=crypto-dorea-cashback');
    }

    print('  
       <main>
            <h1 class="!p-5 !text-sm !font-bold">Fund Campaign</h1> </br>
    ');

    if(isset($_GET['cashbackName'])){
        $cashbackName = sanitize_key($_GET['cashbackName']) ?? null;
        print("<h3 class='!pl-5 !text-xs !font-bold'>Campaign Name: ". $cashbackName . "</h3> </br>");
    }

    print('
             <p id="errorMessg" style="display: none" class="!pl-5"></p>
            
            <div class="!container !mx-auto !pl-5 !pt-2 !pb-5 !shadow-transparent !text-center !rounded-md">
              
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
                 <button class="!p-3 !w-64 !bg-[#faca43] !rounded-md" id="doreaFund" style="">Fund Campaign</button>
                </div>
               
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
add_action('wp_ajax_dorea_contract_address', 'dorea_contract_address');

function dorea_contract_address()
{
    if(isset($_POST['data'])) {

        // get Json Data
        $json_data = stripslashes($_POST['data']);
        $json = json_decode($json_data);

        $campaignName = sanitize_key($json->campaignName);
        $doreaContractAddress = get_option($campaignName . '_contract_address') ?? null;

        $contractAddress = trim(htmlspecialchars(sanitize_text_field($json->contractAddress)));
        if ($doreaContractAddress) {
            // update contract adddress of specific campaign
            update_option($campaignName . '_contract_address', $contractAddress);
        } else {
            // set contract adddress into option
            add_option($campaignName . '_contract_address', $contractAddress);
        }

        $contractAmount = trim(htmlspecialchars(sanitize_text_field($json->contractAmount)));
        if ($contractAmount) {

            $campaignInfo = get_transient($campaignName);
            $campaignInfo['contractAmount'] = $contractAmount;
            set_transient($campaignName, $campaignInfo);

        }
    }

}