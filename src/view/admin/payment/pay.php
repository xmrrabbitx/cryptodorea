<?php

use Cryptodorea\DoreaCashback\controllers\checkoutController;
use Cryptodorea\DoreaCashback\controllers\usersController;
use Cryptodorea\DoreaCashback\controllers\expireCampaignController;
use Cryptodorea\DoreaCashback\utilities\ethHelper;


/**
 * Campaign payment list users
 * @throws \GuzzleHttp\Exception\GuzzleException
 */
function dorea_admin_pay_campaign():void
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

    // load admin css styles
    wp_enqueue_style('DOREA_ADMIN_STYLE',plugins_url('/cryptodorea/css/pay.css'));

    // load campaign credit scripts
    wp_enqueue_script('DOREA_PAYMENT_SCRIPT', plugins_url('/cryptodorea/js/payment.js'), array('jquery', 'jquery-ui-core'));

    static $qualifiedUserEthers;
    static $qualifiedWalletAddresses;
    static $fundOption;

    print("
        <main>
            <h1 class='!p-5 !text-sm !font-bold'>Payment</h1> </br>
            <h2 class='!pl-5 !text-sm !font-bold'>Get Paid in Ethereum</h2> </br>
    ");

    if(isset($_GET['cashbackName'])){
        $cashbackName = sanitize_key($_GET['cashbackName']) ?? null;

        $cashbackInfo = get_transient($cashbackName) ?? null;
        if(!$cashbackInfo){
            wp_redirect('admin.php?page=crypto-dorea-cashback');
        }
        if(isset($cashbackInfo['mode'])){
            if($cashbackInfo['mode'] === "on"){
                $mode = "checked";
            }else{
                $mode = '';
            }
        } else {
            $mode = '';
        }

        print("
            <h3 class='!pl-5 !text-xs !font-bold'>Campaign: ". $cashbackName . "</h3> </br>
            <div class='!container !pl-5 !pt-2 !pb-5 !shadow-transparent !rounded-md'>
            <div class='!pr-5 !text-right'>
                <span class='!pr-1'>disable</span> 
                <label class='switch'>
                  <input id='doreaSwitchcCampaign' type='checkbox' $mode>
                  <span class='slider round'></span>
                </label>
                <input id='doreaCampaignNameSwitch' type='hidden' name='$cashbackName'>
                <span class='!pl-1'>enable</span>
            </div>
        ");
    }


    /**
     * show errors
     */
    print("            
            <p class='!pl-5' id='dorea_error' style='display:none;'></p>
            <p class='!pl-5' id='dorea_success' style='display:none;'></p>
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

    $pagination = sanitize_key($_GET['pagination']) ?? 0;

    $cryptoAmount = $cashbackInfo['cryptoAmount'];
    $userList = get_option("dorea_campaigns_users_" . $cashbackName);
    $checkoutController = new checkoutController;

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
                  it starts at ".$checkoutController->timestamToDate($cashbackName).".
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

        //test data
        /*
        $userList = []; // for test
        $usersList = []; // for test
        for ($i=0;$i<373;$i++){
            $userList[] = $i;
            $usersList[] = $i;
        }
        */

        // pagination set to 100 queries
        for ($i = $j; $i <= ($pagination * 100)-1; $i++) {

            if ($i <= count($userList) - 1) {

                //var_dump($userList);
                //var_dump($userList[$i]);

                $users = $userList[$i];

                $campaignUser = get_option('dorea_campaigninfo_user_' . $users);

                $ethBasePrice = bcdiv(1 , ethHelper::ethPrice(),10);
                //hypothetical price of eth _ get this from an online service
                //$ethBasePrice = 0.0004;

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
                        $qualifiedPurchasesTotal = number_format(array_sum($validPurchases),10);

                        $userEther = number_format(((($qualifiedPurchasesTotal * $cryptoAmount) / 100) * $ethBasePrice), 10);

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

                            print("<span class='xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block'>Amount to be Paid</span><span class='!pl-3 !col-span-1 xl:!text-sm lg:!text-sm md:!text-sm sm:!text-sm'>" . bcsub($userEther, 0, 5) . " ETH</span>");


                            print ("<span class='xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block'>Status</span><div class='!pl-3 !pt-1 !col-span-1 xl:!mx-auto lg:!mx-auto md:!mx-auto sm:!mx-auto  !mx-0 !float-left'>");

                            if (sprintf("%.10f", (float)array_sum($totalEthers)) <= sprintf("%.10f", (float)$contractAmount) && $totalPurchases >= $cashbackInfo['shoppingCount']) {

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
                            $doreaContractAddress = get_option($cashbackName . '_contract_address');
                            if (!empty($totalEthers)) {

                                // check for funding campaign
                                if (sprintf("%.10f", (float)array_sum($totalEthers)) > sprintf("%.10f", (float)$contractAmount)) {

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

            if ($fundOption) {
                print("
                    <!-- Fund Campaign -->
                    <div class='!mx-auto !text-center !mt-5'>
                        <button id='dorea_fund'  class='campaignPayment_ !p-3 !w-64 !bg-[#faca43] !rounded-md'>Fund Campaign</button>
                    </div>
                ");

                // calculate remaining amount eth to pay
                $remainingAmount = bcsub((float)array_sum($totalEthers), number_format((float)$contractAmount, 10), 10);

                // load campaign credit scripts
                wp_enqueue_script('DOREA_FUND_SCRIPT', plugins_url('/cryptodorea/js/fund.js'), array('jquery', 'jquery-ui-core'));

                // pass params value for deployment
                $params = array(
                    'contractAddress' => $doreaContractAddress,
                    'campaignName' => $cashbackName,
                    'remainingAmount' => $remainingAmount,
                );
                wp_localize_script('DOREA_FUND_SCRIPT', 'param', $params);

                print ('
                    <!-- failed campaign payment modal -->
                    <div id="failBreakModal" class="!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-10 !rounded-md !text-center !border" style="display: none">
                        <p class="!text-base">The last payment was interrupted. <br> Please refresh the page...</p>
                        <div class="!mt-5">
                            <button id="failBreakReload" class="!bg-[#faca43] !p-[9px] !ml-5 !rounded-md">Reload</button>
                        </div>
                    </div>
                ');

                // load fail break script
                wp_enqueue_script('DOREA_FUNDFAILBREAK_SCRIPT', plugins_url('/cryptodorea/js/fundFailBreak.js'), array('jquery', 'jquery-ui-core'));
                $param = array(
                    'contractAddress' => $doreaContractAddress,
                );
                wp_localize_script('DOREA_FUNDFAILBREAK_SCRIPT', 'params', $param);

            } else {
                print("
                <!-- Pay Campaign -->
                <div class='!mx-auto !text-center !mt-5'>
                      <button id='dorea_pay'  href='#' class='campaignPayment_ !p-3 !w-64 !bg-[#faca43] !rounded-md'>Pay Campaign</button>
                </div>
            ");

                // load campaign credit scripts
                wp_enqueue_script('DOREA_PAY_SCRIPT', plugins_url('/cryptodorea/js/pay.js'), array('jquery', 'jquery-ui-core'));

                $qualifiedWalletAddresses = json_encode($qualifiedWalletAddresses);

                // pass params value for deployment
                $params = array(
                    'contractAddress' => $doreaContractAddress,
                    'campaignName' => $cashbackName,
                    'qualifiedWalletAddresses' => $qualifiedWalletAddresses,
                    'qualifiedUserEthers' => $qualifiedUserEthers,
                    'cryptoAmount' => $cryptoAmount,
                    'usersList' => $usersList,
                    'totalPurchases' => $userTotalPurchases,
                );
                wp_localize_script('DOREA_PAY_SCRIPT', 'param', $params);

                print ('
                <!-- failed campaign payment modal -->
                <div id="failBreakModal" class="!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-10 !rounded-md !text-center !border" style="display: none">
                    <p class="!text-base">The last payment was interrupted. <br> Please refresh the page...</p>
                    <div class="!mt-5">
                        <button id="failBreakReload" class="!bg-[#faca43] !p-[9px] !ml-5 !rounded-md">Reload</button>
                    </div>
                </div>
            ');
                // load fail break script
                wp_enqueue_script('DOREA_PAYFAILBREAK_SCRIPT', plugins_url('/cryptodorea/js/payFailBreak.js'), array('jquery', 'jquery-ui-core'));

                wp_localize_script('DOREA_PAYFAILBREAK_SCRIPT', 'params', $params);
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
                    <a class="!col-span-1 !mt-0 !pl-0 !focus:ring-0 !hover:text-[#ffa23f] campaignPayment_" id="dorea_pagination" href="' . esc_url(admin_url('/admin.php?page=dorea_payment&cashbackName=' . $cashbackName) . '&pagination=' . $pagination - 1) . '">
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

        print(' <div class="!mt-0 !mr-0 ">' . $pagination. '</div>');
        if (($pagination * 100) <= count($userList) - 1) {
            // forward arrow pagination
            print('      
                <div class="">
                     <a class="!col-span-1 !mt-0 !pl-0 !focus:ring-0 !hover:text-[#ffa23f] campaignPayment_" id="dorea_pagination" href="' . esc_url(admin_url('/admin.php?page=dorea_payment&cashbackName=' . $cashbackName) . '&pagination=' . $pagination + 1)  . '">
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
    if(isset($_POST['data'])) {

        // get Json Data
        $json = stripslashes($_POST['data']);
        $json = json_decode($json);

        if ($json) {
            $campaignInfoUser = get_transient(sanitize_text_field($json->campaignName));
            if(sanitize_text_field($json->mode) === "on"){
                $mode = "on";
            }else  if(sanitize_text_field($json->mode) === "off"){
                $mode = "off";
            }

            $campaignInfoUser['mode'] = $mode;
            set_transient(sanitize_text_field($json->campaignName),$campaignInfoUser);

        }
    }
}

/**
 * fund campaign & get the new balance
 */
add_action('wp_ajax_dorea_fund', 'dorea_fund');
function dorea_fund():void
{
    if(isset($_POST['data'])) {

        // get Json Data
        $json = stripslashes($_POST['data']);
        $json = json_decode($json);

        if ($json) {
            $campaignInfoUser = get_transient(sanitize_text_field($json->campaignName));
            $campaignInfoUser['contractAmount'] = $json->balance;

            $amount = $json->amount;
            $totalPurchases = $json->totalPurchases;

            set_transient($json->campaignName, $campaignInfoUser);

            if (isset($json->usersList)) {
                $usersList = $json->usersList;
                $users = new usersController();
                $users->is_paid($json->campaignName, $usersList, $amount, $totalPurchases);

            }
        }
    }
}

/**
 * pay campaign
 */
add_action('wp_ajax_dorea_pay', 'dorea_pay');
function dorea_pay():void
{
    if(isset($_POST['data'])) {

        // get Json Data
        $json = stripslashes($_POST['data']) ?? null;
        $json = json_decode($json);

        if (isset($json)) {

            $campaignInfo = get_transient($json->campaignName);

            // convert wei to ether
            $campaignInfo['contractAmount'] = $json->balance;
            set_transient($json->campaignName, $campaignInfo);

            // check is_paid
            $users = new usersController();
            $users->is_claimed($json->userList, $json->campaignName, $json->claimedAmount, $json->totalPurchases);

        }
    }
}