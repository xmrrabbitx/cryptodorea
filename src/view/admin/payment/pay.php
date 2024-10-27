<?php

use Cryptodorea\Woocryptodorea\controllers\usersController;
use Cryptodorea\Woocryptodorea\controllers\expireCampaignController;

/**
 * Campaign payment list users
 */
function dorea_admin_pay_campaign():void
{
    // load admin css styles
    wp_enqueue_style('DOREA_ADMIN_STYLE',plugins_url('/woo-cryptodorea/css/pay.css'));

    static $qualifiedUserEthers;
    static $qualifiedWalletAddresses;
    static $fundOption;

    $cashbackName = $_GET['cashbackName'] ?? null;
    $cashbackInfo = get_transient($cashbackName) ?? null;
    $pagination = $_GET['pagination'] ?? 0;
    $pageIndex = $_GET['pageIndex'] ?? 1;

    print("
        <main>
            <div class='!container !pl-5 !pt-2 !pb-5 !shadow-transparent  !rounded-md'>
            <h1 class='!p-5 !text-sm !font-bold'>Payment</h1> </br>
            <h2 class='!pl-5 !text-sm !font-bold'>Get Paid in Ethers</h2> </br>
            
            <p id='dorea_error' style='display:none;'></p>
            <p id='dorea_success' style='display:none;'></p>
    ");

    // check if no campaign existed!
    if(!$cashbackInfo){
        print("
            <!-- error on no campaign -->
            <div class='!text-center !text-sm !mx-auto !w-96 !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
                 <svg class='size-6 text-rose-400' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                     <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                </svg>
                <p class='!pt-3 !pb-2 !text-balance'>
                  no campaign choosen. please select or create one in main page! 
                </p>
               
            </div>");
        return;
    }


    $cryptoAmount = $cashbackInfo['cryptoAmount'];
    $userList = get_option("dorea_campaigns_users_" . $cashbackName);

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

        if($pagination <= count($userList)-1) {

            //foreach ($userList as $users) {
            for ($i = $pagination; $i <= $pagination; $i++) {

                $users = $userList[$i];
                $campaignUser = get_option('dorea_campaigninfo_user_' . $users);

                //hypothetical price of eth _ get this from an online service
                $ethBasePrice = 0.0004;

                if ($campaignUser) {
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
                                             Claimed Amount
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
                    print("<span class='!pl-3 !col-span-1'>" . esc_html($users) . "</span> ");
                    print("<span class='!pl-3 !col-span-1'>" . esc_html(substr($campaignUser[$cashbackName]['walletAddress'], 0, 4) . "****" . substr($campaignUser[$cashbackName]['walletAddress'], 36, 6)) . "</span>");
                    print("<span class='!pl-3 !col-span-1'>" . esc_html($campaignUser[$cashbackName]['purchaseCounts']) . "</span>");

                    if (isset($campaignUser[$cashbackName]['claimedReward'])) {

                        print("<span class='!pl-3 !col-span-1 !text-sm'>" . esc_html($campaignUser[$cashbackName]['claimedReward']) . " ETH</span>");

                    } else {
                        print("<span class='!pl-3 !col-span-1 !text-sm'>0 ETH</span>");
                    }

                    $userEther = number_format(((($qualifiedPurchasesTotal * $cryptoAmount) / 100) * $ethBasePrice), 10);

                    $totalEthers[] = $userEther;

                    print ("<span class='!pl-3 !pt-1 !col-span-1 !mx-auto'>");

                    if (sprintf("%.10f", (float)array_sum($totalEthers)) <= sprintf("%.10f", (float)$contractAmount) && array_sum($totalPurchases) >= $cashbackInfo['shoppingCount']) {

                        // set qualified users to pay
                        $qualifiedUserEthers[] = $userEther;
                        $qualifiedWalletAddresses[] = $campaignUser[$cashbackName]['walletAddress'];
                        $usersList[] = $users;

                        print("
                             <svg class='size-5 text-green-500' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                                 <path fill-rule='evenodd' d='M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z' clip-rule='evenodd' />
                             </svg>
                        ");

                    } else if (array_sum($totalPurchases) < $cashbackInfo['shoppingCount']) {
                        print('  
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                              </svg>
                        ');
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

                    // get contract address of campaign
                    $doreaContractAddress = get_option($cashbackName . '_contract_address');
                    if (!empty($totalEthers)) {

                        // check for funding campaign
                        if (sprintf("%.10f", (float)array_sum($totalEthers)) > sprintf("%.10f", (float)$contractAmount)) {

                            $fundOption = true;
                        }

                    }
                    /*else {


                        print ("
                           <!-- error on no users -->
                           <div class='!text-center !text-sm !mx-auto !w-96 !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
                               <svg class='size-6 text-rose-400' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                                   <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                               </svg>
                               <p class='!pt-3 !pb-2'>
                                   there is no users to pay in the loyalty campaign!
                               </p>              
                           </div>
                        ");

                    }*/

                }

            }

            if ($fundOption) {
                print("
                  <!-- Fund Again -->
                  <div class='!mx-auto !text-center !mt-5'>
                       <button id='dorea_fund'  class='campaignPayment_ !p-3 !w-64 !bg-[#faca43] !rounded-md'>Fund Again</button>
                  </div>
            ");
                var_dump($usersList);
                // calculate remaining amount eth to pay
                $remainingAmount = bcsub((float)array_sum($totalEthers), (float)$contractAmount, 10);

                // load campaign credit scripts
                wp_enqueue_script('DOREA_PAY_SCRIPT', plugins_url('/woo-cryptodorea/js/fund.js'), array('jquery', 'jquery-ui-core'));

                // pass params value for deployment
                $params = array(
                    'contractAddress' => $doreaContractAddress,
                    'campaignName' => $cashbackName,
                    'remainingAmount' => $remainingAmount,
                );
                wp_localize_script('DOREA_PAY_SCRIPT', 'param', $params);

            } else {
                print("
                  <!-- Fund Again -->
                  <div class='!mx-auto !text-center !mt-5'>
                       <button id='dorea_pay'  href='#' class='campaignPayment_ !p-3 !w-64 !bg-[#faca43] !rounded-md'>Pay Campaign</button>
                  </div>
            ");

                // load campaign credit scripts
                wp_enqueue_script('DOREA_PAY_SCRIPT', plugins_url('/woo-cryptodorea/js/pay.js'), array('jquery', 'jquery-ui-core'));

                $qualifiedWalletAddresses = json_encode($qualifiedWalletAddresses);

                // pass params value for deployment
                $params = array(
                    'contractAddress' => $doreaContractAddress,
                    'campaignName' => $cashbackName,
                    'qualifiedWalletAddresses' => $qualifiedWalletAddresses,
                    'qualifiedUserEthers' => $qualifiedUserEthers,
                    'cryptoAmount' => $cryptoAmount,
                    'usersList' => $usersList,
                    'totalPurchases' => $totalPurchases,
                );
                wp_localize_script('DOREA_PAY_SCRIPT', 'param', $params);

            }

        }
        if($pagination+1 <= count($userList)-1) {
            print('
                <a class="!col-span-1 !pl-2 xl:!block lg:!block md:!block sm:!block !hidden !focus:ring-0 !hover:text-[#ffa23f] campaignPayment_" id="dorea_pagination" href="' . esc_url(admin_url('/admin.php?page=dorea_payment&cashbackName=' . $cashbackName) . '&pagination=' . $pagination + 1) . '&pageIndex=' . $pageIndex + 1 . '">
                    <div class="!mt-0 !mr-1 !float-left">' . $pageIndex + 1 . '</div>
                    <div class="!float-left">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                    </svg>
                    </div>
                </a>
            ');
        }else{
            print('
                <a class="!col-span-1 !pl-2 xl:!block lg:!block md:!block sm:!block !hidden !focus:ring-0 !hover:text-[#ffa23f] campaignPayment_" id="dorea_pagination" href="' . esc_url(admin_url('/admin.php?page=dorea_payment&cashbackName=' . $cashbackName) . '&pagination=' . $pagination - 1) . '&pageIndex=' . $pageIndex - 1 . '">
                    <div class="!float-left">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
                        </svg>
                    </div>
                    <div class="!mt-0 !ml-1 !float-left">' . $pageIndex - 1 . '</div>
                </a>
            ');
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

add_action('admin_post_dorea_claimed', 'dorea_claimed');
function dorea_claimed():void
{

    // check if cashback claimed or not
    $json_data = file_get_contents('php://input');
    $json = json_decode($json_data);

    if(isset($json)) {

        $campaignInfo = get_transient($json->campaignName);

        // convert wei to ether
        $campaignInfo['contractAmount'] = $json->balance;
        set_transient($json->campaignName, $campaignInfo);

        // check is_paid
        $users = new usersController();
        $users->is_claimed($json->userList, $json->campaignName, $json->claimedAmount, $json->totalPurchases);

    }
}