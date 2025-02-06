<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * wp on each user request
 */
use Cryptodorea\DoreaCashback\controllers\checkoutController;

add_action('woocommerce_account_content','doreaAccount', 10);
function doreaAccount()
{
    // load status user modal
    doreaUserStatusCampaign();

    // add module type to script
    add_filter('script_loader_tag', 'add_type_userStatusCampaign', 10, 3);
    function add_type_userStatusCampaign($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('DOREA_USERSTATUSCAMPAIGN_SCRIPT' !== $handle) {
            return $tag;
        }

        $position = strpos($tag, 'src="') - 1;
        // change the script tag by adding type="module" and return it.
        $outTag = substr($tag, 0, $position) . ' type="module" ' . substr($tag, $position);

        return $outTag;
    }
}

add_action('wp','doreaRequest', 10);
/**
 * @throws Exception
 */
function doreaRequest()
{
    // check on Authentication
    if(is_user_logged_in()) {

        // autoremove deleted campaigns
        $checkout = new checkoutController();
        $checkout->autoRemove();

        /**
         * add Dorea menu into my account menu
         */
        function dorea_cashback_menu($items)
        {
            // Remove the logout menu item.
            $logout = $items['customer-logout'];
            unset($items['customer-logout']);

            // Insert your dorea cashback menu item
            $items['dorea_cashbback_menu'] = __('Dorea Cashback', 'cryptodorea');

            // Insert back the logout item.
            $items['customer-logout'] = $logout;

            return $items;
        }
        add_filter('woocommerce_account_menu_items', 'dorea_cashback_menu');
    }
}


/**
 * a modal to show the status of claimed cashback campaigns
 */
function doreaUserStatusCampaign():void
{

    /**
     * load necessary libraries files
     * tailwind css
     */
    // load campaign credit scripts
    wp_enqueue_script('DOREA_CAMPAIGNCREDIT_SCRIPT', 'https://cdn.tailwindcss.com', array('jquery', 'jquery-ui-core'),
        array(),
        1,
        true
    );

    // load claim campaign style
    wp_enqueue_style('DOREA_USERSTATUSCAMPAIGN_STYLE', DOREA_PLUGIN_URL . ('css/doreaUserStatusCampaign.css'),
        array(),
        1
    );

    $campaignUser = get_option('dorea_campaigninfo_user_' . wp_get_current_user()->user_login);

    static $sumUserEthers;

    if ($campaignUser) {

        foreach ($campaignUser as $campaignName => $campaignValue) {

            if (isset($campaignValue['claimedReward'])) {

                $sumUserEthers[] = $campaignValue['claimedReward'];

            }

        }

        if($sumUserEthers){
            print('
               <div id="doreaClaimError" class="!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-7 !rounded-md !text-center !border">            
                   <span id="doreaCloseError">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 !text-rose-400 !cursor-pointer !hover:text-rose-200 !float-right">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                      </svg>
                   </span>  
                              
                   <h5 class="!bold">Crypto Dorea</h5>
                   <h6 class="!mt-3">Claimed Rewards: ' . esc_html(array_sum($sumUserEthers)) . ' ETH </h6>
                                       
               </div>
            ');
        }
        else {
            print('
                       <div id="doreaClaimError" class="!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-7 !rounded-md !text-center !border">            
                           <span id="doreaCloseError">
                               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 !text-rose-400 !cursor-pointer !hover:text-rose-200 !float-right">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                               </svg>
                           </span>  
                              
                           <h5 class="bold">Sorry</h5>
                           <h6 class="">You have any rewards yet!</h6>      
                        </div>
                ');
        }
    }
    else {
        print("
                <div id='doreaClaimError' class='!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 !shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-7 !rounded-md !text-center !border'>            
                   <span id='doreaCloseError'>
                       <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6 !text-rose-400 !cursor-pointer !hover:text-rose-200 !float-right'>
                           <path stroke-linecap='round' stroke-linejoin='round' d='M6 18 18 6M6 6l12 12' />
                       </svg>
                   </span>  
                   <h5 class='bold'>Sorry</h5>
                   <h6>You didn't join any Campaigns yet!</h6>      
                </div>
            ");
    }


    // load claim campaign scripts
    wp_enqueue_script('DOREA_USERSTATUSCAMPAIGN_SCRIPT', DOREA_PLUGIN_URL . ('js/doreaUserStatusCampaign.js'), array('jquery', 'jquery-ui-core'),
        array(),
        1,
        true
    );

}