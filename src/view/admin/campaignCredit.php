<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
    wp_enqueue_style('DOREA_CAMPAIGNCREDIT_STYLE',DOREA_PLUGIN_URL . ('css/doreaCampaignCredit.css'),
        array(),
        1,
    );

    if(isset($_GET['_wpnonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
        if (!empty($_GET['cashbackName']) && wp_verify_nonce($nonce, 'deploy_campaign_nonce')) {

            $campaignName = sanitize_key($_GET['cashbackName']);

            $doreaContractAddress = get_option('dorea_' . $campaignName . '_contract_address');
            if ($doreaContractAddress) {
                wp_redirect('admin.php?page=crypto-dorea-cashback');
            }

            $ajaxNonce = wp_create_nonce("campaign_credit_nonce");

            // load campaign credit scripts
            wp_enqueue_script('DOREA_CAMPAIGNCREDIT_SCRIPT', DOREA_PLUGIN_URL . ('js/doreaCampaignCredit.js'), array('jquery', 'jquery-ui-core'),
                array(),
                1,
                true
            );


            $params = array(
                'campaignName' => $campaignName,
                "ajaxNonce"=>$ajaxNonce,
                'ajax_url' => admin_url('admin-ajax.php'),
            );
            wp_localize_script('DOREA_CAMPAIGNCREDIT_SCRIPT', 'param', $params);

            // add module type to scripts
            add_filter('script_loader_tag', 'add_type_campaigncredit', 10, 3);
            function add_type_campaigncredit($tag, $handle, $src)
            {
                // if not your script, do nothing and return original $tag
                if ('DOREA_CAMPAIGNCREDIT_SCRIPT' !== $handle) {
                    return $tag;
                }

                $position = strpos($tag, 'src="') - 1;
                // change the script tag by adding type="module" and return it.
                $outTag = substr($tag, 0, $position) . ' type="module" ' . substr($tag, $position);

                return $outTag;
            }

        } else {
            wp_redirect('admin.php?page=crypto-dorea-cashback');
        }
    }
    else{
        wp_redirect(wp_get_referer());
        exit;
    }

    print('  
       <main>
            <h1 class="!p-5 !text-sm !font-bold">Fund Campaign</h1> </br>
            
    ');

    if(isset($_GET['cashbackName'])){
        $cashbackName = sanitize_key($_GET['cashbackName']) ?? null;
        print("<h3 class='!pl-5 !text-xs !font-bold'>Campaign Name: ". esc_html($cashbackName) . "</h3> </br>");
    }

    print('
            <p id="errorMessg" style="display: none" class="!pl-5"></p>
            <p class="!pl-5" id="dorea_success" style="display:none;"></p>

            <!-- Warning before transaction! -->
            <div id="beforeTrxModal" class="!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-10 !rounded-md !text-center !border" style="display: none">
               <p class="!text-sm !mt-3">Please Do not leave the page <br> until the transaction is complete!</p>
            </div>
            
            <div class="!container !mx-auto !pl-5 !pt-2 !pb-5 !shadow-transparent !text-center !rounded-md">
              
              <h2 class="!text-center !text-lg !divide-y !mt-5">Crypto Dorea Cashback</h2>
              <hr class="border-1 !w-64 !text-center !dark:bg-gray-700 !w-48 1h-1 !mx-auto !mt-2">
              
              <div class="!grid !grid-cols-1 !justify-items-center">
              
                <div class="!col-span-1 !w-64 !mt-10">
                    <span class="">
                        <label class="!text-pretty !text-left !float-left">Notes: Ethers must be in the Ether format, e.g 0.0004</label>
                    </span>
                    <span class="">
                        <input class="!rounded-md !w-64 !mt-5 !p-2 !focus:ring-green-500 !border-hidden !bg-white" id="creditAmount" type="text" placeholder="amount">
                    </span>
                </div>
                <div class="!col-span-1 !w-12/12 !mt-5">
                 <button class="!p-3 !w-64 !bg-[#faca43] !rounded-md" id="doreaFund" style="">Fund Campaign</button>
                </div>
               
                <p class="!mt-10" id="dorea_metamask_error" style="display:none;color:#ff5d5d;"></p>
                <p class="!mt-10" id="dorea_fund_error" style="display:none;color:#ff5d5d;"></p>
                
              </div>
            </div>
    ');

    print ('
        <!-- failed campaign payment modal -->
        <div id="failBreakModal" class="!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-10 !rounded-md !text-center !border" style="display: none">
            <p class="!text-sm">The last payment was interrupted. <br> Please refresh the page...</p>
            <div class="!mt-5">
                <button id="failBreakReload" class="!bg-[#faca43] !p-[9px] !ml-5 !rounded-md">Reload</button>
            </div>
        </div>
        <div id="doreaFailedBreakStatusLoading" role="status" class="!fixed !top-[10%] z-10 inset-x-0 flex flex-col items-center justify-center" style="display: none">
            <div>
                <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-yellow-400" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
            </div>
            <p class="!text-center !mt-3">please wait until the sync is done...</p>
            <p id="doreaTimerLoading" class="!text-center !mt-3" style="display: none"></p>
        </div>
        
        </main>
        
    ');
    $params = array(
        'ajax_url' => admin_url('admin-ajax.php'),
    );
    wp_localize_script('DOREA_DEPLOYFAILBREAK_SCRIPT', 'param', $params);

    // load fail break script
    wp_enqueue_script_module('DOREA_DEPLOYFAILBREAK_SCRIPT',DOREA_PLUGIN_URL . ('js/doreaDeployFailBreak.js'), array('jquery', 'jquery-ui-core'));

}

/**
 * Campaign Credit smart contract address
 */
add_action('wp_ajax_dorea_contract_address', 'dorea_contract_address');

function dorea_contract_address()
{
    if(isset($_GET['_wpnonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
        if (isset($_POST['data']) && wp_verify_nonce($nonce, 'campaign_credit_nonce')) {

            // get Json Data
            $json_data = sanitize_text_field(wp_unslash($_POST['data']));
            $json = json_decode($json_data);

            $campaignName = sanitize_key($json->campaignName);
            $doreaContractAddress = get_option('dorea_' . $campaignName . '_contract_address') ?? null;

            $contractAddress = trim(htmlspecialchars(sanitize_text_field($json->contractAddress)));
            if ($doreaContractAddress) {
                // update contract adddress of specific campaign
                update_option('dorea_' . $campaignName . '_contract_address', $contractAddress);
            } else {
                // set contract adddress into option
                add_option('dorea_' . $campaignName . '_contract_address', $contractAddress);
            }

            $contractAmount = trim(htmlspecialchars(sanitize_text_field($json->contractAmount)));
            if ($contractAmount) {

                $campaignInfo = get_transient('dorea_' . $campaignName);
                $campaignInfo['contractAmount'] = $contractAmount;
                set_transient($campaignName, $campaignInfo);

            }
        }
    }
}