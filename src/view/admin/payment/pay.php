<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Cryptodorea\DoreaCashback\controllers\checkoutController;
use Cryptodorea\DoreaCashback\controllers\productController;
use Cryptodorea\DoreaCashback\controllers\usersController;
use Cryptodorea\DoreaCashback\controllers\expireCampaignController;
use Cryptodorea\DoreaCashback\utilities\ethHelper;


/**
 * Campaign payment list users
 */
function dorea_admin_pay_campaign():void
{
    /**
     * load necessary libraries files
     * tailwind css v3.4.16
     * the official CDN URL: https://cdn.tailwindcss.com
     * Source code: https://github.com/tailwindlabs/tailwindcss/tree/v3.4.16
     */
    wp_enqueue_script('DOREA_CORE_STYLE', DOREA_PLUGIN_URL . 'js/tailWindCssV3416.min.js', array('jquery', 'jquery-ui-core'),
        array(),
        1,
        true
    );

    // update admin footer
    function add_admin_footer_text() {
        return 'Crypto Dorea: <a class="!underline" href="https://cryptodorea.io">cryptodorea.io</a>';
    }
    add_filter( 'admin_footer_text', 'add_admin_footer_text', 11 );
    function update_admin_footer_text() {
        return 'Version 1.1.1';
    }
    add_filter( 'update_footer', 'update_admin_footer_text', 11 );

    // load admin css styles
    wp_enqueue_style('DOREA_MAIN_STYLE',DOREA_PLUGIN_URL . ('/css/doreaPay.css'),
        array(),
        1,
    );

    static $qualifiedUserEthers;
    static $qualifiedWalletAddresses;
    static $fundOption;

    print("
        <main class='doreaContent'>
            <h1 class='!p-5 !text-sm !font-bold'>Payment</h1> </br>
            <h2 class='!pl-5 !text-sm !font-bold'>Get Paid in Ethereum</h2> </br>
    ");

    if(isset($_GET['_wpnonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
        if (isset($_GET['cashbackName']) && wp_verify_nonce($nonce, 'payment_nonce')) {
            $cashbackName = sanitize_key(wp_unslash($_GET['cashbackName'])) ?? null;

            $cashbackInfo = get_transient('dorea_' . $cashbackName) ?? null;

            if (!$cashbackInfo) {
                wp_redirect('admin.php?page=crypto-dorea-cashback');
            }
            if (isset($cashbackInfo['mode'])) {
                if ($cashbackInfo['mode'] === "on") {
                    $mode = "checked";
                } else {
                    $mode = '';
                }
            } else {
                $mode = '';
            }

            // load campaign credit scripts
            wp_enqueue_script('DOREA_PAYMENT_SCRIPT', DOREA_PLUGIN_URL . ('js/doreaPayment.js'), array('jquery', 'jquery-ui-core'),
                array(),
                1,
                true
            );
            $switchNonce = wp_create_nonce("switchCampaign_nonce");
            $switchParams = array(
                "switchAjaxNonce" => $switchNonce,
                'ajax_url' => admin_url('admin-ajax.php'),
            );
            wp_localize_script('DOREA_PAYMENT_SCRIPT', 'switchParams', $switchParams);

            print(("<h3 class='!pl-5 !text-xs'><span class='!font-bold'>Campaign: </span>" . esc_html($cashbackName) . "</h3></br>
                <div><span class='!pl-5 !text-xs !font-bold'>Campaign Balance: </span>".esc_html(number_format($cashbackInfo['contractAmount'],5))." ETH</div>
                <div class='!container !pl-5 !pt-2 !pb-5 !shadow-transparent !rounded-md'>
                    <div class='!pr-5 !text-right'><span class='!pr-1'>disable</span> 
                        <label class='switch'>
                            <input id='doreaSwitchcCampaign' type='checkbox' " . esc_html($mode) . ">
                            <span class='slider round'></span>
                        </label>
                        <input id='doreaCampaignNameSwitch' type='hidden' name='" . esc_html($cashbackName) . "'>
                        <span class='!pl-1'>enable</span>
                    </div>
            "));
        } else {
            wp_redirect('admin.php?page=crypto-dorea-cashback');
        }
    }

    /**
     * show errors
     */
    print("            
            <p class='!pl-5' id='dorea_error' style='display:none;'></p>
            <p class='!pl-5' id='dorea_success' style='display:none;'></p>

            <!-- Warning before transaction! -->
            <div id='doreaBeforeTrxModal' class='!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-10 !rounded-md !text-center !border' style='display: none'>
               <p class='!text-sm !mt-3'>Please Do not leave the page <br> until the transaction is complete!</p>
            </div>
            
            <!-- Warning on interrupted transaction! -->
            <div id='doreaRjectMetamask' class='!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-10 !rounded-md !text-center !border' style='display: none'>
               <p class='!text-sm !mt-3'>The last payment was interrupted. <br>Please reject the payment on metamask otherwise you may lose your money...</p>
            </div>
    ");

    /**
     * check if no campaign existed!
     */
    if(!isset($_GET['cashbackName'])){
        print("
            <!-- error on no campaign -->
            <div class='!text-center !text-sm !mx-auto !w-96 !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
                 <svg class='size-6 text-rose-400' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                     <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                </svg>
                <p class='!pt-3 !pb-2 !text-balance'>
                    No campaign was chosen. Please select or create one on the main page!
                </p>
               
            </div>
        ");
        return;
    }

    if(isset($_GET['pagination'])){
    $pagination = sanitize_key($_GET['pagination']) ?? 0;
    }

    $cryptoAmount = $cashbackInfo['cryptoAmount'];
    $userList = get_option("dorea_campaigns_users_" . $cashbackName);
    $checkoutController = new checkoutController;

    $categoryNonce = wp_create_nonce("categoryCampaign_nonce");
    $categoryParams = array(
        "categoryAjaxNonce" => $categoryNonce,
        'ajax_url' => admin_url('admin-ajax.php'),
        "campaignName"=>$cashbackName
    );
    wp_localize_script('DOREA_PAYMENT_SCRIPT', 'categoryParams', $categoryParams);

    // get product categories
    $productCategories = new productController();
    $productCategoriesUser = get_option('doreaCategoryProducts' . $cashbackName) == true ? get_option('doreaCategoryProducts' . $cashbackName) : [];

    /**
     * Filter Products Categories
     */
    print("
        <div class='!grid !grid-cols-1 !gap-2'>
           <div id='doreaProductCategoriesIcon' class='flex hover:!text-amber-500'>
           
           Filer Product Categories 
           <!-- arrow down -->
           <svg id='doreaProductCategoriesArrowDown' stroke-width='1.5' stroke='currentColor' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='size-4 bi bi-chevron-down !mt-1 !ml-2' viewBox='0 0 16 16'>
              <path fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708'/>
           </svg>
           <!-- arrow up -->
           <svg id='doreaProductCategoriesArrowUp' xmlns='http://www.w3.org/2000/svg' width='16' height='16' stroke-width='1.5' stroke='currentColor' class='size-4 bi bi-chevron-up !mt-1 !ml-2' viewBox='0 0 16 16'>
              <path fill-rule='evenodd' d='M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708z'/>
           </svg>
           </div>
                <div id='doreaProductCategoriesList' class='!pb-5 !p-3  !w-auto !ml-1 !mr-1 !p-2 !col-span-1 !mt-2 !rounded-sm !border border-slate-700 !float-left' style='display: none'>  

    ");

    foreach ($productCategories->listCategories() as $categories){
        if(in_array($categories, $productCategoriesUser)){
           $checked  = "checked";
        }
        else{
          $checked = "";
        }
        print("
              <div class='!flex !mt-1'>
                  <div class='!w-1/12 !ml-1'>
                      <input class='doreaProductCategoriesValues !accent-white !text-white !mt-1 !cursor-pointer' type='checkbox' value='" . esc_html($categories) . "' $checked>
                  </div>
                  <label class='doreaProductCategoriesValues !w-11/12 !pl-3 !text-left !ml-0 xl:!text-sm lg:!text-sm md:!text-sm sm:!text-sm !text-[12px] !float-left !content-center !whitespace-break-spaces !cursor-pointer'>".esc_html($categories)."</label>
              </div>
        ");

    }

    print(" 
        <button id='doreaProductCategoriesSubmit' class='!mt-5 !ml-1'>save changes</button>
        </div>
          </div>
              </div>
    ");

    if($checkoutController->checkTimestamp($cashbackName) === "expired"){
        print ("
            <!-- error on campaign expired! -->
            <div class='!text-center !text-sm !mx-auto xl:!w-96 lg:!w-96 md:!w-96 sm:!w-96 !w-80 !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
                 <svg class='size-6 text-rose-400' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                     <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                </svg>
                <p class='!pt-2 !pb-2 !break-words !text-balance'>
                  the campaign Date expired. please create another campaign!
                </p>
            </div>
        ");
    }
    elseif ($checkoutController->checkTimestamp($cashbackName) === "notStarted"){
        print ("
            <!-- error on campaign expired! -->
            <div class='!text-center !text-sm !mx-auto xl:!w-96 lg:!w-96 md:!w-96 sm:!w-96 !w-80 !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
                 <svg class='size-6 text-rose-400' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                     <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                </svg>
                <p class='!pt-2 !pb-2 !break-words !text-balance'>
                  the campaign is not started yet!
                  it starts at ".esc_html($checkoutController->timestampToDate($cashbackName) ).".
                </p>
            </div>
        ");
    }
    else if($userList === false){
        print ("
            <!-- error on no users! -->
            <div class='!text-center !text-sm !mx-auto xl:!w-96 lg:!w-96 md:!w-96 sm:!w-96 !w-80 !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
                 <svg class='size-6 text-rose-400' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                     <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                </svg>
                <p class='!pt-2 !pb-2 !break-words !text-balance'>
                  No users have participated in the loyalty campaign yet!
                </p>
            </div>
        ");
    }
    else {

        $totalEthers = [];
        $usersList = [];

        $contractAmount = $cashbackInfo['contractAmount'];
        $shoppingCount = $cashbackInfo['shoppingCount'];

        $addtoPaymentSection = true;

        $j = $pagination - 1 === 0 ? 0 : ($pagination-1) * 100;


        // pagination set to 100 queries
        for ($i = $j; $i <= ($pagination * 100)-1; $i++) {

            if ($i <= count($userList) - 1) {

                $users = $userList[$i];

                $campaignUser = get_option('dorea_campaigninfo_user_' . $users);

                // base eth price
                //$ethBasePrice = bcdiv(1 , ethHelper::ethPrice(),10);
                $ethBasePrice = 0.0004;
                if($ethBasePrice) {
                    if ($campaignUser && $campaignUser[$cashbackName]['purchaseCounts'] >= $shoppingCount) {

                        $purchaseCounts[] = true;

                        // calculate final price in ETH format
                        $qualifiedPurchases = array_chunk($campaignUser[$cashbackName]['total'], $shoppingCount);

                        $validPurchases = [];
                        array_map(function ($value) use ($shoppingCount, &$validPurchases) {

                            if (count($value) == $shoppingCount) {
                                $value = array_sum($value);
                                // calculate percentage of each value
                                $validPurchases[] = $value;
                            }
                        }, $qualifiedPurchases);

                        $totalPurchases = count($validPurchases) * $shoppingCount;
                        $qualifiedPurchasesTotal = number_format(array_sum($validPurchases),5);

                        $userEther = number_format(((($qualifiedPurchasesTotal * $cryptoAmount) / 100) * $ethBasePrice), 5);

                        $userTotalPurchases[] = $totalPurchases;
                        $totalEthers[] = $userEther;

                        if($totalPurchases >= $cashbackInfo['shoppingCount']) {

                            $usersList[] = $users;

                            if ($addtoPaymentSection) {
                                print('
                                <div class="!grid !grid-cols-1 !ml-5 !w-3/3 !mr-5 !mt-3 !p-10 !gap-3 !text-left !rounded-xl  !bg-white !shadow-sm !border">
                                    <div class="!col-span-1 !grid !grid-cols-5 xl:!grid lg:!grid  md:!grid  sm:!grid !hidden">
                                         <span class="!text-center !pl-3">
                                            Username
                                         </span>
                                         <span class="!text-center">
                                             Wallet Address
                                         </span>
                                         <span class="!text-center">
                                            Purchase Count
                                         </span>
                                         <span class="!text-center">
                                             Amount to be Paid
                                           
                                         </span>
                                          <span class="!text-center">
                                             Eligibility to Pay
                                         </span>
                                    </div>
                                    <hr>
                                ');
                                $addtoPaymentSection = false;
                            }

                            print("<div  class='!col-span-1 !grid xl:!grid-cols-5 lg:!grid-cols-5 md:!grid-cols-5 sm:!grid-cols-5 !grid-cols-2 !pt-3 xl:!text-center lg:!text-center md:!text-center sm:!text-center !text-left !gap-y-5 !gap-5'>");
                            print("<span class='xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block '>Username</span><span class='!pl-3 !col-span-1'>" . esc_html($users) . "</span>");
                            print("<span class='xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block '> Wallet Address</span><span class='!pl-3 !col-span-1'>" . esc_html(substr($campaignUser[$cashbackName]['walletAddress'], 0, 4) . "****" . substr($campaignUser[$cashbackName]['walletAddress'], 36, 6)) . "</span>");
                            print("<span class='xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block '>Purchase Counts</span><span class='!pl-3 !col-span-1'>" . esc_html($totalPurchases) . "</span>");

                            print("<span class='xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block'>Amount to be Paid</span><span class='!pl-3 !col-span-1 xl:!text-sm lg:!text-sm md:!text-sm sm:!text-sm'>" . esc_html(bcsub($userEther, 0, 5)) . " ETH</span>");


                            print ("<span class='xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block'>Status</span><div class='!pl-3 !pt-1 !col-span-1 xl:!mx-auto lg:!mx-auto md:!mx-auto sm:!mx-auto  !mx-0 !float-left'>");

                            if (sprintf("%.5f", (float)array_sum($totalEthers)) <= sprintf("%.5f", (float)$contractAmount) && $totalPurchases >= $cashbackInfo['shoppingCount']) {

                                // set qualified users to pay
                                $qualifiedUserEthers[] = $userEther;
                                $qualifiedWalletAddresses[] = $campaignUser[$cashbackName]['walletAddress'];

                                print("
                                     <svg class='size-5 !text-green-500' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                                         <path fill-rule='evenodd' d='M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z' clip-rule='evenodd' />
                                     </svg>
                                ");

                            } else if ($totalPurchases < $cashbackInfo['shoppingCount']) {
                                print('  
                                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                  </svg>
                                ');
                            } else {
                                print("
                                 <svg class='size-5 !text-amber-500' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                                     <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                                 </svg>
                                ");
                            }

                            print ("
                                    </div>
                                </div>
                                <hr class='xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block'>
                            ");

                            // get contract address of campaign
                            $doreaContractAddress = get_option("dorea_" . $cashbackName . '_contract_address');
                            if (!empty($totalEthers)) {

                                // check for funding campaign
                                if (sprintf("%.5f", (float)array_sum($totalEthers)) > sprintf("%.5f", (float)$contractAmount)) {

                                    $fundOption = true;
                                }

                            }
                        }
                    }
                }

            }

        }

        if(!empty($usersList)) {

            // error on empty purchase
            if (!in_array(true, $purchaseCounts)) {
                print ("
                <!-- notif on campaign paid! -->
                <div class='!text-center !text-sm !mx-auto !w-96 !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
                     <svg class='size-6 text-rose-400' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                         <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                    </svg>
                    <p class='!pt-3 !pb-2 !leading-7'>
                      the loyalty campaign has been successfully paid!
                      please check the transactions list from main page.
                    </p>
                   
                </div>
            ");
                return;
            }

            // fund option triggers otherwise pay option triggers
            if ($fundOption) {
                print("
                    <!-- Fund Campaign -->
                    <div class='!mx-auto !text-center !mt-5'>
                        <button id='dorea_fund'  class='campaignPayment_ !p-3 !w-64 !bg-[#faca43] !rounded-md'>Fund Campaign</button>
                    </div>
                ");

                // calculate remaining amount eth to pay
                $remainingAmount = bcsub(number_format((float)array_sum($totalEthers),10), number_format((float)$contractAmount, 10), 10);

                // load campaign credit scripts
                wp_enqueue_script('DOREA_FUND_SCRIPT', DOREA_PLUGIN_URL . ('/js/doreaFund.js'), array('jquery', 'jquery-ui-core'),
                    array(),
                    1,
                    true
                );

                $ajaxNonce = wp_create_nonce("fundCampaign_nonce");

                // pass params value for deployment
                $fundParams = array(
                    'contractAddress' => $doreaContractAddress,
                    'campaignName' => $cashbackName,
                    'remainingAmount' => $remainingAmount,
                    "fundAjaxNonce"=>$ajaxNonce,
                    'ajax_url' => admin_url('admin-ajax.php'),
                );
                wp_localize_script('DOREA_FUND_SCRIPT', 'param', $fundParams);


                // add module type to script
                add_filter('script_loader_tag', 'add_type_fund', 10, 3);
                function add_type_fund($tag, $handle, $src)
                {
                    // if not your script, do nothing and return original $tag
                    if ('DOREA_FUND_SCRIPT' !== $handle) {
                        return $tag;
                    }

                    $position = strpos($tag, 'src="') - 1;
                    // change the script tag by adding type="module" and return it.
                    $outTag = substr($tag, 0, $position) . ' type="module" ' . substr($tag, $position);

                    return $outTag;
                }

                print ('
                    <!-- failed campaign payment modal -->
                    <div id="doreaFailBreakModal" class="!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-10 !rounded-md !text-center !border" style="display: none">
                        <p class="!text-sm">The last payment was interrupted. <br> Please refresh the page...</p>
                        <div class="!mt-5">
                            <button id="doreaFailBreakLoading" class="!bg-[#faca43] !p-[9px] !ml-5 !rounded-md">Reload</button>
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
                ');

                // load fail break script
                wp_enqueue_script('DOREA_FUNDFAILBREAK_SCRIPT', DOREA_PLUGIN_URL . ('js/doreaFundFailBreak.js'), array('jquery', 'jquery-ui-core'),
                    array(),
                    1,
                    true
                );

                $failBreakFundParam = array(
                    'contractAddress' => $doreaContractAddress,
                    'ajax_url' => admin_url('admin-ajax.php'),
                );
                wp_localize_script('DOREA_FUNDFAILBREAK_SCRIPT', 'params', $failBreakFundParam);

                // add module type to scripts
                add_filter('script_loader_tag', 'add_type_fundfailbreak', 10, 3);
                function add_type_fundfailbreak($tag, $handle, $src)
                {
                    // if not your script, do nothing and return original $tag
                    if ('DOREA_FUNDFAILBREAK_SCRIPT' !== $handle) {
                        return $tag;
                    }

                    $position = strpos($tag, 'src="') - 1;
                    // change the script tag by adding type="module" and return it.
                    $outTag = substr($tag, 0, $position) . ' type="module" ' . substr($tag, $position);

                    return $outTag;
                }

            }
            else {
                print("
                    <!-- Pay Campaign -->
                    <div class='!mx-auto !text-center !mt-5'>
                          <button id='dorea_pay'  href='#' class='campaignPayment_ !p-3 !w-64 !bg-[#faca43] !rounded-md'>Pay Campaign</button>
                    </div>
                ");

                // load campaign credit scripts
                wp_enqueue_script('DOREA_PAY_SCRIPT', DOREA_PLUGIN_URL . ('js/doreaPay.js'), array('jquery', 'jquery-ui-core'),
                    array(),
                    1,
                    true
                );

                // add module type to script
                add_filter('script_loader_tag', 'add_type_pay', 10, 3);
                function add_type_pay($tag, $handle, $src)
                {
                    // if not your script, do nothing and return original $tag
                    if ('DOREA_PAY_SCRIPT' !== $handle) {
                        return $tag;
                    }

                    $position = strpos($tag, 'src="') - 1;
                    // change the script tag by adding type="module" and return it.
                    $outTag = substr($tag, 0, $position) . ' type="module" ' . substr($tag, $position);

                    return $outTag;
                }

                $qualifiedWalletAddresses = wp_json_encode($qualifiedWalletAddresses);

                print ('
                    <!-- transaction expired warning modal -->
                    <div id="trxExpired" class="!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-10 !rounded-md !text-center !border" style="display: none">
                        <p class="!text-base">Warning: The transaction is expired, you may lose your money! <br> please reject previous transaction on metamask and try again...</p>
                        <div class="!mt-5">
                        </div>
                    </div>
                ');

                $ajaxNonce = wp_create_nonce("payCampaign_nonce");
                $trxId = doreaTrxIdsGenerate($cashbackName);

                // pass params value for deployment
                $payParams = array(
                    'contractAddress' => $doreaContractAddress,
                    'campaignName' => $cashbackName,
                    'qualifiedWalletAddresses' => $qualifiedWalletAddresses,
                    'qualifiedUserEthers' => $qualifiedUserEthers,
                    'cryptoAmount' => $cryptoAmount,
                    'usersList' => $usersList,
                    'totalPurchases' => $userTotalPurchases,
                    "payAjaxNonce" => $ajaxNonce,
                    "trxId" => $trxId,
                    'ajax_url' => admin_url('admin-ajax.php'),
                );
                wp_localize_script('DOREA_PAY_SCRIPT', 'param', $payParams);

                print ('
                    <!-- failed campaign payment modal -->
                    <div id="doreaFailBreakModal" class="!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-10 !rounded-md !text-center !border" style="display: none">
                        <p class="!text-sm">The last payment was interrupted. <br> Please refresh the page...</p>
                        <div class="!mt-5">
                            <button id="doreaFailBreakLoading" class="!bg-[#faca43] !p-[9px] !ml-5 !rounded-md">Reload</button>
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
                ');

                // load fail break script
                wp_enqueue_script('DOREA_PAYFAILBREAK_SCRIPT', DOREA_PLUGIN_URL . ('js/doreaPayFailBreak.js'), array('jquery', 'jquery-ui-core'),
                    array(),
                    1,
                    true
                );

                wp_localize_script('DOREA_PAYFAILBREAK_SCRIPT', 'params', $payParams);

                // add module type to scripts
                add_filter('script_loader_tag', 'add_type_payfailbreak', 10, 3);
                function add_type_payfailbreak($tag, $handle, $src)
                {
                    // if not your script, do nothing and return original $tag
                    if ('DOREA_PAYFAILBREAK_SCRIPT' !== $handle) {
                        return $tag;
                    }

                    $position = strpos($tag, 'src="') - 1;
                    // change the script tag by adding type="module" and return it.
                    $outTag = substr($tag, 0, $position) . ' type="module" ' . substr($tag, $position);

                    return $outTag;
                }

            }
        }
        else{
            print ("
                <!-- error on no eligible users! -->
                <div class='!text-center !text-sm !mx-auto xl:!w-96 lg:!w-96 md:!w-96 sm:!w-96 !w-80 !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
                     <svg class='size-6 text-rose-400' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                         <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                    </svg>
                    <p class='!pt-2 !pb-2 !break-words !text-balance'>
                      There are no eligible users! 
                      You can check the transaction list from the main page.
                    </p>
                </div>
            ");
            return;
        }

    }

    if($userList && !empty($usersList)) {
        print('<div class="!grid !grid-cols-3 !w-16 !text-center">');
        // pagination navigation
        if ($pagination - 1 !== 0) {
            // backward arrow pagination
            print('
               <div class="">
                    <a class="!col-span-1 !mt-0 !pl-0 !focus:ring-0 !hover:text-[#ffa23f] campaignPayment_" id="dorea_pagination" href="' . esc_url(admin_url('/admin.php?page=dorea_payment&cashbackName=' . $cashbackName) . '&_wpnonce='.$nonce.'&pagination=' . $pagination - 1) . '">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
                        </svg>
                    </a>
                </div>
            ');
        }
        else{
            // blank space
            print('<div class="!col-span-1"></div>');
        }

        print(' <div class="!mt-0 !mr-0 ">' . esc_html($pagination). '</div>');
        if (($pagination * 100) <= count($userList) - 1) {
            // forward arrow pagination
            print('      
                <div class="">
                     <a class="!col-span-1 !mt-0 !pl-0 !focus:ring-0 !hover:text-[#ffa23f] campaignPayment_" id="dorea_pagination" href="' . esc_url(admin_url('/admin.php?page=dorea_payment&cashbackName=' . $cashbackName) . '&_wpnonce='.$nonce.'&pagination=' . $pagination + 1)  . '">
                       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                           <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                        </svg>
                     </a>
                 </div>
            ');
        }
        print("</div>");
    }

    print("
            </div>
        </main>
    ");
}


/**
 * switch on/off campaign
 */
add_action('wp_ajax_dorea_switchCampaign', 'dorea_switchCampaign');
function dorea_switchCampaign()
{
    if(isset($_GET['_wpnonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
        if (isset($_POST['data']) && wp_verify_nonce($nonce, 'switchCampaign_nonce')) {

            // get Json Data
            $json = sanitize_text_field(wp_unslash($_POST['data']));
            $json = json_decode($json);

            if ($json) {
                $campaignInfoUser = get_transient(sanitize_text_field('dorea_' . $json->campaignName));
                if (sanitize_text_field($json->mode) === "on") {
                    $mode = "on";
                } else if (sanitize_text_field($json->mode) === "off") {
                    $mode = "off";
                }

                $campaignInfoUser['mode'] = $mode;
                set_transient(sanitize_text_field('dorea_' . $json->campaignName), $campaignInfoUser);

            }
        }
    }
}

/**
 * fund campaign & get the new balance
 */
add_action('wp_ajax_dorea_fund', 'dorea_fund');
function dorea_fund():void
{
    if(isset($_GET['_wpnonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
        if (isset($_POST['data']) && wp_verify_nonce($nonce, 'fundCampaign_nonce')) {

            // get Json Data
            $json = sanitize_text_field(wp_unslash($_POST['data']));
            $json = json_decode($json);

            if ($json) {
                $campaignInfoUser = get_transient(sanitize_text_field('dorea_' . $json->campaignName));
                $campaignInfoUser['contractAmount'] = $json->balance;

                $amount = $json->amount;
                $totalPurchases = $json->totalPurchases;

                set_transient('dorea_' . $json->campaignName, $campaignInfoUser);

                if (isset($json->usersList)) {
                    $usersList = $json->usersList;
                    $users = new usersController();
                    $users->is_paid($json->campaignName, $usersList, $amount, $totalPurchases);

                }
            }
        }
    }
}

/**
 * pay campaign ajax
 */
add_action('wp_ajax_dorea_pay', 'dorea_pay');
function dorea_pay():void
{
    if(isset($_GET['_wpnonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
        if (isset($_POST['data']) && wp_verify_nonce($nonce, 'payCampaign_nonce')) {

            // get Json Data
            $json = sanitize_text_field(wp_unslash($_POST['data'])) ?? null;
            $json = json_decode($json);

            if (isset($json)) {

                $campaignInfo = get_transient('dorea_' . $json->campaignName);

                // convert wei to ether
                $campaignInfo['contractAmount'] = $json->balance;
                set_transient('dorea_' . $json->campaignName, $campaignInfo);

                // check is_paid
                $users = new usersController();
                $users->is_claimed($json->userList, $json->campaignName, $json->claimedAmount, $json->totalPurchases, $json->trxId);

            }
        }
    }
}

/**
 * Generate transactions Ids
 */
function doreaTrxIdsGenerate($cashbackName)
{
    $paymentTrxIds = get_option('dorea_paymentTrxIds');
    $trxHash = "0x" . bin2hex(random_bytes(32));
    if(isset($paymentTrxIds)) {
        if(isset($paymentTrxIds[$cashbackName])) {
            if (!in_array($trxHash, $paymentTrxIds[$cashbackName])) {
                return $trxHash;
            } else {
                return doreaTrxIdsGenerate($cashbackName);
            }
        }else{
            return $trxHash;
        }
    }
}

/**
 * filter product categories ajax
 */
add_action('wp_ajax_dorea_category', 'dorea_category');
function dorea_category():void
{
    if (isset($_GET['_wpnonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
        if (isset($_POST['data']) && wp_verify_nonce($nonce, 'categoryCampaign_nonce')) {
            $json = sanitize_text_field(wp_unslash($_POST['data'])) ?? null;
            $json = json_decode($json);
            $campaignName = sanitize_text_field(wp_unslash($json->campaignName));
            $categories = $json->categories;
            if(!empty($categories)) {
                get_option('doreaCategoryProducts' . $campaignName) == true ? update_option('doreaCategoryProducts' . $campaignName, $categories) : add_option('doreaCategoryProducts' . $campaignName,$categories);
            }
        }
    }
}