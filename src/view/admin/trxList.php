<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Transactions List of campaigns
 */
function dorea_admin_trx_campaign():void
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
    function add_admin_footer_text()
    {
       return 'Crypto Dorea: <a class="!underline" href="https://cryptodorea.io">cryptodorea.io</a>';
    }

    add_filter('admin_footer_text', 'add_admin_footer_text', 11);
    function update_admin_footer_text()
    {
       return 'Version 1.1.1';
    }

    add_filter('update_footer', 'update_admin_footer_text', 11);

    // load admin css styles
    wp_enqueue_style('DOREA_MAIN_STYLE', DOREA_PLUGIN_URL . ('css/doreaTrxList.css'),
    array(),
    1
    );

    if(isset($_GET['_wpnonce'])) {

        $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce']));

        if (isset($_GET['cashbackName']) && isset($_GET['pagination']) && wp_verify_nonce($nonce, 'trx_list_nonce')) {

            $cashbackName = sanitize_text_field(wp_unslash($_GET['cashbackName'])) ?? null;
            $cashbackInfo = get_transient('dorea_' . $cashbackName);
            if (!$cashbackInfo) {
                wp_redirect('admin.php?page=crypto-dorea-cashback');
            }
            $pagination = sanitize_text_field(wp_unslash($_GET['pagination'])) ?? 0;
            $claimedUsers = get_option("dorea_claimed_users_" . $cashbackName) ?? null;

        }
    }

            print("
                <main class='doreaContent'>
                    <h1 class='!p-5 !text-sm !font-bold'>Transactions List</h1> </br>
                    <h2 class='!pl-5 !text-sm !font-bold'>Claimed Ethers</h2> </br>
            ");


            if (isset($_GET['cashbackName'])) {
                $cashbackName = sanitize_key($_GET['cashbackName']) ?? null;
                print(wp_kses_post("<h3 class='!pl-5 !text-xs !font-bold'>Campaign Name: " . esc_html($cashbackName) . "</h3> </br>"));
            }

            // check if no campaign existed!
            if (!isset($_GET['cashbackName'])) {
                print("
                    <!-- error on no campaign -->
                    <div class='!text-center !text-sm !mx-auto !w-96 !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
                         <svg class='size-6 !text-rose-400' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                             <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                        </svg>
                        <p class='!pt-3 !pb-2 !text-balance'>
                           No campaign was chosen. Please select or create one on the main page!
                        </p>
                       
                    </div>");
                return;
            }

            // check if no transactions existed!
            if (empty($claimedUsers)) {
                print("
                    <!-- error on no campaign -->
                    <div class='!text-center !text-sm !mx-auto !mx-auto xl:!w-96 lg:!w-96 md:!w-96 sm:!w-96 !w-80  !p-5 !rounded-xl !mt-10 !bg-[#faca43] !shadow-transparent'>
                         <svg class='size-6 text-rose-400' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor'>
                             <path fill-rule='evenodd' d='M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z' clip-rule='evenodd' />
                        </svg>
                        <p class='!pt-3 !pb-2 !break-words !text-balance'>
                          No transactions have been recorded <br> for this campaign so far!
                        </p>
                       
                    </div>");
                return;
            }

            // show errors
            print("      
               <div class='!container !pl-5 !pt-2 !pb-5 !shadow-transparent  !rounded-md'>      
                    <p id='dorea_error' style='display:none;'></p>
                    <p id='dorea_success' style='display:none;'></p>
            ");

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

            $j = $pagination - 1 === 0 ? 0 : $pagination;
            if (!empty($claimedUsers) && $pagination <= count($claimedUsers)) {
                for ($i = $j; $i <= ($pagination * 100) - 1; $i++) {
                    if ($i <= count($claimedUsers) - 1) {
                        $users = $claimedUsers[$i];
                        $campaignUser = get_option('dorea_campaigninfo_user_' . $users);

                        print("<div class='!col-span-1 !grid xl:!grid-cols-4 lg:!grid-cols-4 md:!grid-cols-4 sm:!grid-cols-4 !grid-cols-2 !pt-3 !text-center'>");
                        print("<span class='xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block !pt-3'>Username</span><span class='!pl-3 !pt-3 !col-span-1'>" . esc_html($users) . "</span> ");
                        print("<span class='xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block !pt-3'> Wallet Address</span><span class='!pl-3 !pt-3 xl:!col-span-2 lg:!col-span-2 md:!col-span-2 sm:!col-span-2 !col-span-1 !break-all'>" . esc_html($campaignUser[$cashbackName]['walletAddress']) . "</span>");
                        print("<span class='xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block !pt-3'>Claimed Ethers</span><span class='!pl-3 !pt-3 !col-span-1'>" . esc_html($campaignUser[$cashbackName]['claimedReward']) . "</span>");
                        print("</div>");
                        print('<hr class="xl:!hidden lg:!hidden  md:!hidden  sm:!hidden !block">');
                    }
                }

                print('<div class="!grid !grid-cols-3 !w-16 !text-center">');
                // pagination navigation
                if ($pagination - 1 !== 0) {
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
                } else {
                    // blank space
                    print('<div class="!col-span-1"></div>');
                }
                print(' <div class="!mt-0 !mr-0 ">' . esc_html($pagination) . '</div>');
                if (($pagination * 100) <= count($claimedUsers) - 1) {
                    // forward arrow pagination
                    print('      
                <div class="">
                     <a class="!col-span-1 !mt-0 !pl-0 !focus:ring-0 !hover:text-[#ffa23f] campaignPayment_" id="dorea_pagination" href="' . esc_url(admin_url('/admin.php?page=transactions_list&cashbackName=' . $cashbackName) . '&pagination=' . $pagination + 1) . '">
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