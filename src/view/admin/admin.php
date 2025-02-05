<?php

use Cryptodorea\DoreaCashback\controllers\cashbackController;

include_once WP_PLUGIN_DIR . '/cryptodorea/src/view/modals/deleteCampaign.php';

/**
 * add menu options to admin panels
 */
add_action('admin_menu', 'dorea_add_menu_page');
function dorea_add_menu_page(): void
{
    //var_dump(get_option('paymentTrxIds'));
    //var_dump(get_transient('test_f945dbe'));

    $logoIco_path = plugin_dir_path(__FILE__) . 'icons/doreaLogo_ico.svg';

    if (file_exists($logoIco_path)) {

        $logo_content_array = file($logoIco_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $logo_content = implode("\n", $logo_content_array);

        $base64_encoded = base64_encode($logo_content);

        /**
         * Dorea Cash Back Main Menu
         */
        add_menu_page(
            'Crypto Dorea', // Page title
            'Crypto Dorea', // Menu title
            'manage_options',  // Capability required to access
            'crypto-dorea-cashback',  // Menu slug (unique identifier)
            'dorea_main_page_content',  // Callback function to display page content
            'data:image/svg+xml;base64,' . $base64_encoded,  // Icon URL or dashicon class
            10  // Menu position
        );

        /**
         * Campaign Menu
         */
        add_submenu_page(
            'crypto-dorea-cashback',
            'Create Campaign',
            'Create Campaign',
            'manage_options',
            'campaigns',
            'dorea_cashback_campaign_content'
        );

        /**
         * Campaign Credit Menu
         */
        add_submenu_page(
            'crypto-dorea-cashback',
            'Fund Campaign',
            'Fund Campaign',
            'manage_options',
            'credit',
            'dorea_cashback_campaign_credit'
        );

        /**
         * Dorea campaign payment page
         */
        add_submenu_page(
            'crypto-dorea-cashback',
            'Pay Campaign',
            'Pay Campaign',
            'manage_options',
            'dorea_payment',
            'dorea_admin_pay_campaign'
        );

        /**
         * Dorea campaign transactions list
         */
        add_submenu_page(
            'crypto-dorea-cashback',
            'Transactions List',
            'Transactions List',
            'manage_options',
            'transactions_list',
            'dorea_admin_trx_campaign'
        );

        /**
         * Dorea campaign Help Page
         */
        add_submenu_page(
            'crypto-dorea-cashback',
            'Help',
            'Help',
            'manage_options',
            'Help',
            'dorea_admin_help_campaign'
        );

    }

}

/**
 *  main page content
 */
function dorea_main_page_content():void
{

    $logo_path = plugins_url('/icons/doreaLogo.svg', __FILE__);

    // update admin footer
    function add_admin_footer_text() {
        return 'Crypto Dorea: <a class="!underline" href="https://cryptodorea.io">cryptodorea.io</a>';
    }
    add_filter( 'admin_footer_text', 'add_admin_footer_text', 11 );
    function update_admin_footer_text() {
        return 'Version 1.0.0';
    }
    add_filter( 'update_footer', 'update_admin_footer_text', 11 );

    $cashback = new cashbackController();
    $cashbackList = $cashback->list();

    // load admin css styles
    wp_enqueue_style('DOREA_ADMIN_STYLE',plugins_url('/cryptodorea/css/admin.css'),
        array(),
        1,
    );

    print("
        <main>
            <h1 class='!p-5 !text-sm !font-bold'>
                Home
            </h1> 
            </br>
    ");

    if ($cashbackList) {

        print("
            <h2 class='!pl-5 !text-sm !break-words !text-balance !font-bold'>
                DOREA CASHBACK GROWS YOUR BUSINESS TO THE MOON 🚀  
            </h2> 
            <p class='!pl-5 !text-sm !mt-2 !text-slate-500 !break-words !text-balance'>Create Cashback Campaign for the Most Loyal Customers!</p>
            </br>");

        print("
            <div class='!container !mx-auto !pl-5 !pt-2 !pb-5 !shadow-transparent !text-center !rounded-md'>
        ");
        foreach ($cashbackList as $campaignName) {
            print("  
                <div class='!mr-5 !pl-3 !p-0 !pr-5 !pt-10 !pb-10 !mt-3 !rounded-xl !bg-white !shadow-sm !border'>
                   <div class='!grid xl:!grid-cols-12 lg:!grid-cols-12 md:!grid-cols-12 sm:!grid-cols-12 !grid-cols-12 !gap-1 !flex !items-center'>
                    <div class='xl:!col-span-11 lg:!col-span-11 !col-span-10 !grid xl:!grid-cols-6 lg:!grid-cols-6 md:!grid-cols-6 sm:!grid-cols-6 !grid-cols-2'>
            ");

            $campName = get_transient($campaignName)['campaignNameLable'];
            print('<span class="!col-span-1 !m-auto !text-center !whitespace-break-spaces">'. esc_html($campName) .'</span>');

            $doreaContractAddress = get_option($campaignName . '_contract_address');

            if ($doreaContractAddress) {
                print ('<span class="!col-span-1 !text-emerald-500 xl:!block lg:!block md:!block sm:!block !hidden inline-block !m-auto">funded</span>');
            } else {
                print('<a class="!col-span-1 xl:!block lg:!block md:!block sm:!block !hidden !pl-2 !focus:ring-0 hover:!text-emerald-500 !text-center" href="' . esc_url(admin_url('admin.php?page=credit&cashbackName=' . $campaignName . '&_wpnonce=' . wp_create_nonce('deploy_campaign_nonce'))) . '"> fund </a>');

                print ('
                    <!-- add column to fill white space in case there is no pay option -->
                    <span class="!col-span-1 xl:!block lg:!block md:!block sm:!block !hidden"></span>
                ');
            }

            // payment page
            if($doreaContractAddress) {
                $nonce = wp_create_nonce('payment_nonce');
                print('
                     <a class="!col-span-1 !pl-2 xl:!block lg:!block md:!block sm:!block !hidden !focus:ring-0 hover:!text-amber-500 !m-auto campaignPayment_" id="campaignPayment_' . esc_js($campaignName) . '_' . esc_js($doreaContractAddress) . '" href="' . esc_url(admin_url('/admin.php?page=dorea_payment&cashbackName=' . esc_html($campaignName) )).'&pagination=1&_wpnonce=' . esc_html($nonce) .'">pay</a>
                ');
            }

            print ('
                <!-- add column to fill white space -->
                <span class="!col-span-1 xl:!block lg:!block md:!block sm:!block !hidden"></span>
            ');

            $trxListNonce = wp_create_nonce('trx_list_nonce');
            print('
                <!-- transactions lits -->
                <div class="!flex !items-center !grid-flex lg:!gap-3 !gap-1 lg:!pl-0 !pl-2 !text-center">
                <span class="!col-span-1 !focus:ring-0 !cursor-pointer">
                   <a title="transactions list" class="hover:!text-amber-500" href="' . esc_url(admin_url('/admin.php?page=transactions_list&cashbackName=' . $campaignName)) . '&pagination=1&_wpnonce=' .esc_html($trxListNonce).'">
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                         </svg>
                   </a>   
                </span>
            ');
            if($doreaContractAddress) {
                print('
                    <!-- success status of campaign -->
                    <span class="col-span-1">
                        <svg class="lg:!size-6 !size-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                          <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                ');
            }else {
                print ('
                    <!-- warning status of campaign -->
                     <span class="col-span-1">
                       <svg class="size-6 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                          <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                       </svg>
                     </span>
                ');
            }

            print ('
                      <span class="">
                        <!-- delete campaign -->
                        <span id="deleteCampaign" class="deleteCampaign_ !focus:ring-0 !cursor-pointer !text-rose-500 hover:!text-rose-700" name="' . esc_url(admin_url('admin-post.php?cashbackName=' . $campaignName . '&action=delete_campaign&_wpnonce=' . wp_create_nonce('delete_campaign_nonce'))) . '">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </span>
                     </span>
            ');

            $campaignInfo = get_transient($campaignName);
            $startDate = gmdate('Y/m/d',$campaignInfo['timestampStart']);
            $startDate = explode('/',$startDate);

            print('
                    <!-- date of created campaign -->
                    <span class="!mt-1">
                        '.esc_html($startDate[0]) . "/" .esc_html($startDate[1])."/". esc_html($startDate[2]) .'
                    </span>
                </div>
            ');

            // payment page
            if($doreaContractAddress) {
                $nonce = wp_create_nonce();
                print('
                    </div>
                    <div class="lg:!col-span-1 !col-span-2">
                    <a class="!col-span-1 !self-center !focus:ring-0 !focus:outline-none !outline-none !text-black hover:!text-amber-500  campaignPayment_" id="campaignPayment_' . esc_js($campaignName) . '_' . esc_js($doreaContractAddress) . '" href="' . esc_url(admin_url('/admin.php?page=dorea_payment&cashbackName=' . $campaignName)) . '&pagination=1&_wp_nonce' .esc_html($nonce).'">
                        <!-- payment-fund campaign page link -->
                        <span class="!float-right">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                              <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                            </svg>
                        </span>
                    </a>
                ');
            }else {
                print('
                    </div>
                    <div class="lg:!col-span-1 !col-span-2">
                    <a class="!col-span-1 !self-center !focus:ring-0 !focus:outline-none !outline-none !text-black hover:!text-amber-500 campaignPayment_" href="' . esc_url(admin_url('admin.php?page=credit&cashbackName=' . $campaignName . '&_wpnonce=' . wp_create_nonce('deploy_campaign_nonce'))) . '">
                        <!-- payment-fund campaign page link -->
                        <span class="!float-right">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                              <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                            </svg>
                        </span>
                    </a>
                ');
            }
            print("
                        </div>
                    </div>   
                </div>
            ");
        }

    } else {
        $campaign_url = wp_nonce_url(esc_url(admin_url("/admin.php?page=campaigns")));
        print('<h3 class="!text-base !text-center !text-gray-400 !mt-16">Your Journey to Web3 Cashback</h3></br><p class="!pt-2 !mt-7 !text-center"> <a class="!basis-12 !p-10 !text-black !hover:text-black lg:!text-[13px] md:!text-[14px] sm:!text-sm !text-[11px] !bg-[#faca43] !text-center !rounded-xl !focus:ring-0 !focus:outline-none !outline-none shadow-md" href="'.esc_url($campaign_url)  .'">Create Your First Cashback Campaign</a></p>');
    }

    // pop up delete campaign modal
    deleteModal();

    print(" 

            </div>  
        </main>
    ");

    print ('
        <!-- transaction expired warning modal -->
        <div id="trxExpired" class="!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-10 !rounded-md !text-center !border" style="display: none">
            <span class="col-span-1">
              <svg class="size-6 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                 <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
            </svg>
            <p class="!text-sm !mt-3">Warning: The transaction is expired! <br> please reject previous transaction <br> on metamask and try again...</p>
            <div class="!mt-5">
            </div>
        </div>
    ');
    // load fail break script
    wp_enqueue_script_module('DOREA_ADMIN_SCRIPT',plugins_url('/cryptodorea/js/admin.js'), array('jquery', 'jquery-ui-core'),
        array(),
        1,
        true
    );

    print ('
        <!-- failed campaign payment modal -->
        <div id="failBreakModal" class="!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-10 !rounded-md !text-center !border" style="display: none">
            </span>
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
           <p class="!text-center !mt-3">please wait until sync is done...</p>
        </div>
    ');

    // load fail break script
    wp_enqueue_script('DOREA_DEPLOYFAILBREAK_SCRIPT',plugins_url('/cryptodorea/js/deployFailBreak.js'), array('jquery', 'jquery-ui-core'),
        array(),
        1,
        true
    );


    // add module type to scripts
    add_filter('script_loader_tag', 'add_type_deployfailbreak', 10, 3);
    function add_type_deployfailbreak($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_DEPLOYFAILBREAK_SCRIPT' !== $handle) {
            return $tag;
        }

        $position = strpos($tag, 'src="') - 1;
        // change the script tag by adding type="module" and return it.
        $outTag = substr($tag, 0, $position) . ' type="module" ' . substr($tag, $position);

        return $outTag;
    }

}

/**
 * Crypto Cashback Campaign
 */
include('campaign.php');

/**
 * Credit
 */
include('campaignCredit.php');

/**
 * Payment Modal
 */
include('payment/pay.php');

/**
 * transactrions list
 */
include('trxList.php');

/**
 * help page
 */
include('help.php');