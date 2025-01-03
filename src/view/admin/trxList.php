<?php

/**
 * Transactions List of campaigns
 */
function dorea_admin_trx_campaign():void
{
    // load admin css styles
    wp_enqueue_style('DOREA_ADMIN_STYLE',plugins_url('/cryptodorea/css/trxList.css'));

    $cashbackName = $_GET['cashbackName'] ?? null;
    $cashbackInfo = get_transient($cashbackName);
    if(!$cashbackInfo){
        wp_redirect('admin.php?page=crypto-dorea-cashback');
    }
    $pagination = $_GET['pagination'] ?? 0;
    $claimedUsers = get_option("dorea_claimed_users_" . $cashbackName) ?? null;

    print("
        <main>
            <div class='!container !pl-5 !pt-2 !pb-5 !shadow-transparent  !rounded-md'>
            <h1 class='!p-5 !text-sm !font-bold'>Transactions List</h1> </br>
            <h2 class='!pl-5 !text-sm !font-bold'>Claimed Ethers</h2> </br>
    ");

    if(isset($_GET['cashbackName'])){
        $cashbackName = sanitize_key($_GET['cashbackName']) ?? null;
        print("<h3 class='!pl-5 !text-xs !font-bold'>Campaign Name: ". $cashbackName . "</h3> </br>");
    }

    // show errors
    print("            
            <p id='dorea_error' style='display:none;'></p>
            <p id='dorea_success' style='display:none;'></p>
    ");

    // check if no campaign existed!
    if(!isset($_GET['cashbackName'])){
        print("
            <!-- error on no campaign -->
            <div class='!text-center !text-sm !mx-auto !w-96 !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
                 <svg class='size-6 !text-rose-400' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                     <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                </svg>
                <p class='!pt-3 !pb-2 !text-balance'>
                  no campaign choosen. please select or create one in main page! 
                </p>
               
            </div>");
        return;
    }

    // check if no transactions existed!
    if(empty($claimedUsers)){
        print("
            <!-- error on no campaign -->
            <div class='!text-center !text-sm !mx-auto !mx-auto xl:!w-96 lg:!w-96 md:!w-96 sm:!w-96 !w-80  !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
                 <svg class='size-6 text-rose-400' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                     <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                </svg>
                <p class='!pt-3 !pb-2 !break-words !text-balance'>
                  there is no transactions </br> on this campaign yet!
                </p>
               
            </div>");
        return;
    }

    print('
       <div class="!grid !grid-cols-1 !ml-5 !w-3/3 !mr-5 !mt-3 !p-10 !gap-3 !text-left !rounded-xl  !bg-white !shadow-sm !border">
             <div class="!col-span-1 !grid-cols-4 xl:!grid lg:!grid  md:!grid  sm:!grid !hidden">
                   <span class="!text-center !pl-3 !col-span-1">
                        Username        
                   </span>
                   <span class="!text-center !col-span-2">
                        Wallet Address                      
                   </span>
                   <span class="!text-center !col-span-1">
                         Clamied Ethers           
                   </span>
             </div>
             <hr class="">
    ');

    $j = $pagination -1 === 0 ? 0 : $pagination;
    if (!empty($claimedUsers) && $pagination <= count($claimedUsers)) {
        for ($i =  $j; $i <= ($pagination * 100) - 1; $i++) {
            if ($i <= count($claimedUsers) - 1) {
                $users = $claimedUsers[$i];
                $campaignUser = get_option('dorea_campaigninfo_user_' . $users);

                print("<div class='!col-span-1 !grid xl:!grid-cols-4 lg:!grid-cols-4 md:!grid-cols-4 sm:!grid-cols-4 !grid-cols-2 !pt-3 !text-center'>");
                print("<span class='xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block !pt-3'>Username</span><span class='!pl-3 !pt-3 !col-span-1'>" . $users . "</span> ");
                print("<span class='xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block !pt-3'> Wallet Address</span><span class='!pl-3 !pt-3 xl:!col-span-2 lg:!col-span-2 md:!col-span-2 sm:!col-span-2 !col-span-1 !break-all'>" . $campaignUser[$cashbackName]['walletAddress'] . "</span>");
                print("<span class='xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block !pt-3'>Claimed Ethers</span><span class='!pl-3 !pt-3 !col-span-1'>" . $campaignUser[$cashbackName]['claimedReward'] . "</span>");
                print("</div>");
                print('<hr class="xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block">');
            }

        }

        print('<div class="!grid !grid-cols-3 !w-16 !text-center">');
        // pagination navigation
        if ($pagination -1 !== 0) {
            // backward arrow pagination
            print('
               <div class="">
                    <a class="!col-span-1 !mt-0 !pl-0 !focus:ring-0 !hover:text-[#ffa23f] campaignPayment_" id="dorea_pagination" href="' . esc_url(admin_url('/admin.php?page=transactions_list&cashbackName=' . $cashbackName) . '&pagination=' . $pagination - 1) . '">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
                        </svg>
                    </a>
                </div>
            ');
        }else{
            // blank space
            print('<div class="!col-span-1"></div>');
        }
        print(' <div class="!mt-0 !mr-0 ">' . $pagination. '</div>');
        if (($pagination * 100) <= count($claimedUsers) - 1) {
            // forward arrow pagination
            print('      
                <div class="">
                     <a class="!col-span-1 !mt-0 !pl-0 !focus:ring-0 !hover:text-[#ffa23f] campaignPayment_" id="dorea_pagination" href="' . esc_url(admin_url('/admin.php?page=transactions_list&cashbackName=' . $cashbackName) . '&pagination=' . $pagination + 1)  . '">
                       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                           <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                        </svg>
                     </a>
                 </div>
            ');
        }
        print("</div>");

    }

    print("</div></main>");

}