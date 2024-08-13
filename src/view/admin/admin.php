<?php

use Cryptodorea\Woocryptodorea\controllers\cashbackController;

/**
 * add menu options to admin panels
 */
add_action('admin_menu', 'dorea_add_menu_page');
function dorea_add_menu_page(): void
{

    //var_dump(wp_readonly("this is readonly"));
    //die("stopppp");
    //var_dump(get_option("adminPaymentTimestamp"));
    //var_dump(delete_option("adminPaymentTimestamp"));
    //var_dump(substr(md5(openssl_random_pseudo_bytes(20)),-7));
    //set_transient("dorea_queue_delete_campaigns","dorea1", 10);
    //set_transient("dorea_queue_delete_campaigns","dorea2");
    //delete_transient("dorea_queue_delete_campaigns");
    ///var_dump(get_option("campaign_list"));
    //delete_option("dorea_queue_delete_campaigns");
    //var_dump(get_option("dorea_queue_delete_campaigns"));
    //var_dump(get_option("dorea_campaigns_users_". "dorea"));
    //var_dump(get_option("dorea_campaigns_users_" . "dorea"));
    //var_dump(delete_option("dorea_campaigninfo_user_" . "usertest1"));
    //var_dump(get_option("dorea_campaigninfo_user_" . "mrrabbit"));

    $logo_path = plugin_dir_path(__FILE__) . 'icons/doreaLogo.svg';

    if (file_exists($logo_path)) {

        $logo_content = file_get_contents($logo_path);
        $base64_encoded = base64_encode($logo_content);

        /**
         * Dorea Cash Back Main Menu
         */
        add_menu_page(
            'Dorea CashBack', // Page title
            'Dorea CashBack', // Menu title
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
            'cashback campaigns',
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
            'campaign credit',
            'manage_options',
            'credit',
            'dorea_cashback_campaign_credit'
        );

        /**
         * Dorea Plans
         */
        add_submenu_page(
            'crypto-dorea-cashback',
            'Plans Page',
            'Dorea Plans',
            'manage_options',
            'dorea_plans',
            'doreaPlans'
        );

    }

    print('<script src="https://cdn.tailwindcss.com"></script>');
}


/**
 *  main page content
 */
function dorea_main_page_content()
{

    $cashback = new cashbackController();
    $cashbackList = $cashback->list();

    print("<h2 class='p-5 text-sm font-bold'>Home</h2> </br>");
    print("
        <h3 class='pl-5 text-sm font-bold'>create cashback campaign for your most loyal customers!</h3> </br>
        <div class='container mx-auto pl-5'>
    ");

    if ($cashbackList) {
        foreach ($cashbackList as &$campaignName) {
            print("  
                <div class='flex flex-row pl-3'>
                    <div class='basis-2/5'>
                        <div class='flex flex-row'>
            ");
            print($campaignName . '<a class="basis-12 pl-2 hover:text-rose-500" href="' . esc_url(admin_url('admin-post.php?cashbackName=' . $campaignName . '&action=delete_campaign&nonce=' . wp_create_nonce('delete_campaign_nonce'))) . '"> delete </a>');

            $doreaContractAddress = get_option($campaignName . '_contract_address');

            if ($doreaContractAddress) {
                print ('funded!</br>');
            } else {
                print('<a class="basis-12 pl-2 hover:text-[#71b227]" href="' . esc_url(admin_url('admin.php?page=credit&cashbackName=' . $campaignName . '&nonce=' . wp_create_nonce('deploy_campaign_nonce'))) . '"> fund </a>' . '</br>');
            }

            if($doreaContractAddress) {
                print('
                  
                        <a class="basis-12 pl-2 hover:text-[#ffa23f] campaignPayment_" id="campaignPayment_' . $campaignName . '_' . $doreaContractAddress . '" href="' . esc_url(admin_url('admin-post.php?cashbackName=' . $campaignName . '&action=pay_campaign')).'">pay</a>
                
                ');
            }
            print("
                    </div>
                        </div>
                        <div class='basis-1/2'>
                        <!-- left blank intentional -->
                        </div>
                </div>
            ");
        }

    } else {
        // remove wordpress prefix on production
        print('<a class="basis-12 pl-4" href="/wordpress/wp-admin/admin.php?page=campaigns">create your first Cashback Reward Campaign</a>');
    }

    print("    
        </div>
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
 * Plans
 */
include('doreaPlans.php');

/**
 * Payment Modal
 */
include('payment/pay.php');
