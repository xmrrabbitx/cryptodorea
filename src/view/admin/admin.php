<?php

use Cryptodorea\DoreaCashback\controllers\cashbackController;

// include necessary files
include_once WP_PLUGIN_DIR . '/cryptodorea/src/view/modals/deleteCampaign.php';

/**
 * add menu options to admin panels
 */
add_action('admin_menu', 'dorea_add_menu_page');
function dorea_add_menu_page(): void
{
    $logo_path = plugin_dir_path(__FILE__) . 'icons/doreaLogo.svg';

    if (file_exists($logo_path)) {

        $logo_content = file_get_contents($logo_path);
        $base64_encoded = base64_encode($logo_content);

        /**
         * Dorea Cash Back Main Menu
         */
        add_menu_page(
            'CryptoDorea', // Page title
            'Home', // Menu title
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
            'Campaign Page',
            'Create Campaigns',
            'manage_options',
            'campaigns',
            'dorea_cashback_campaign_content'
        );

        /**
         * Campaign Credit Menu
         */
        add_submenu_page(
            'crypto-dorea-cashback',
            'Campaign Page',
            'Campaign Credit',
            'manage_options',
            'credit',
            'dorea_cashback_campaign_credit'
        );

        /**
         * Dorea campaign payment list
         */
        add_submenu_page(
            'crypto-dorea-cashback',
            'Campaign Page',
            'Dorea Payment',
            'manage_options',
            'dorea_payment',
            'dorea_admin_pay_campaign'
        );

    }

}

/**
 *  main page content
 */
function dorea_main_page_content():void
{
    $cashback = new cashbackController();
    $cashbackList = $cashback->list();

    // load admin css styles
    wp_enqueue_style('DOREA_ADMIN_STYLE',plugins_url('/cryptodorea/css/admin.css'));

    print("
        <main>
        <h1 class='!p-5 !text-sm !font-bold'>Home</h1> </br>
    ");

    if ($cashbackList) {

        print("
            <h2 class='!pl-5 !text-sm !font-bold'>
                DOREA CASHBACK GROWS YOUR BUSINESS TO THE MOON 🚀  
            </h2> 
            <p class='!pl-5 !text-sm !mt-2 !text-slate-500'>Create Cashback Campaign for the Most Loyal Customers!</p>
            </br>");

        print("
            <div class='!container !mx-auto !pl-5 !pt-2 !pb-5 !shadow-transparent !text-center !rounded-md'>
        ");
        foreach ($cashbackList as &$campaignName) {
            print("  
                <div class='!mr-5 !pl-3 !p-10 !mt-3 !rounded-xl !bg-white !shadow-sm !border'>
                   <div class='!grid xl:!grid-cols-12 lg:!grid-cols-12 md:!grid-cols-12 sm:!grid-cols-12 !grid-cols-12 !gap-1'>
                    <div class='xl:!col-span-11 lg:!col-span-11 !col-span-10 !grid xl:!grid-cols-6 lg:!grid-cols-6 md:!grid-cols-6 sm:!grid-cols-6 !grid-cols-2'>
            ");

            print('<span class="!col-span-1">'.esc_html($campaignName).'</span>');

            $doreaContractAddress = get_option($campaignName . '_contract_address');

            if ($doreaContractAddress) {
                print ('<span class="!col-span-1 !text-emerald-500 xl:!block lg:!block md:!block sm:!block !hidden">funded!</span>');
            } else {
                print('<a class="!col-span-1 xl:!block lg:!block md:!block sm:!block !hidden !pl-2 !focus:ring-0 !hover:text-emerald-500 !text-center" href="' . esc_url(admin_url('admin.php?page=credit&cashbackName=' . $campaignName . '&nonce=' . wp_create_nonce('deploy_campaign_nonce'))) . '"> fund </a>');

                print ('
                    <!-- add column to fill white space in case there is no pay option -->
                    <span class="!col-span-1 xl:!block lg:!block md:!block sm:!block !hidden"></span>
                ');
            }

            // payment page
            if($doreaContractAddress) {
                print('
                     <a class="!col-span-1 !pl-2 xl:!block lg:!block md:!block sm:!block !hidden !focus:ring-0 !hover:text-[#ffa23f] campaignPayment_" id="campaignPayment_' . esc_js($campaignName) . '_' . esc_js($doreaContractAddress) . '" href="' . esc_url(admin_url('/admin.php?page=dorea_payment&cashbackName=' . $campaignName)).'&pagination=0&pageIndex=1">pay</a>
                ');
            }

            print ('
                <span class="!col-span-1 xl:!block lg:!block md:!block sm:!block !hidden"></span>
            ');

            print('
                <div class="!flex !grid-flex lg:!gap-3 !gap-1 lg:!pl-0 !pl-2 !text-center">
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
                        <span id="deleteCampaign" class="deleteCampaign_ !hover:text-rose-500 !focus:ring-0 !cursor-pointer" name="' . esc_url(admin_url('admin-post.php?cashbackName=' . $campaignName . '&action=delete_campaign&nonce=' . wp_create_nonce('delete_campaign_nonce'))) . '">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 !text-rose-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </span>
                     </span>
            ');

            $campaignInfo = get_transient($campaignName);
            $startDate = date('Y/m/d',$campaignInfo['timestampStart']);
            $startDate = explode('/',$startDate);

            print('
                    <!-- date of created campaign -->
                    <span class="!mt-1">
                        '.esc_html($startDate[0]) . "/" .esc_html($startDate[1]) ."/". esc_html($startDate[2]) .'
                    </span>
                </div>
            ');

            // payment page
            if($doreaContractAddress) {
                print('
                    </div>
                    <div class="lg:!col-span-1 !col-span-2">
                    <a class="!col-span-1 !self-center !focus:ring-0 !focus:outline-none !outline-none !text-black !hover:text-[#ffa23f] campaignPayment_" id="campaignPayment_' . esc_js($campaignName) . '_' . esc_js($doreaContractAddress) . '" href="' . esc_url(admin_url('/admin.php?page=dorea_payment&cashbackName=' . $campaignName)) . '&pagination=0&pageIndex=1">
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
                    <a class="!col-span-1 !self-center !focus:ring-0 !focus:outline-none !outline-none !text-black !hover:text-[#ffa23f] campaignPayment_" href="' . esc_url(admin_url('admin.php?page=credit&cashbackName=' . $campaignName . '&nonce=' . wp_create_nonce('deploy_campaign_nonce'))) . '">
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
        print('
                <h3 class="text-base text-center text-gray-400 mt-16">Start your Journey to Web3</h3>
                </br>
                <p class="pt-2 mt-7 text-center">
                    <a class="!basis-12 !p-10 !text-black !hover:text-black lg:!text-[13px] md:!text-[14px] sm:!text-sm !text-[11px] !bg-[#faca43] !text-center !rounded-xl !focus:ring-0 !focus:outline-none !outline-none" href="'.esc_url(admin_url("/admin.php?page=campaigns"))  .'">Create Your First Cashback Campaign</a>
                </p>
        ');
    }

    // pop up delete campaign modal
    deleteModal();

    print(" 

            </div>  
        </main>
    ");


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

