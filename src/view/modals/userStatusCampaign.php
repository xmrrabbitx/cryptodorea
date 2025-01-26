<?php

namespace Cryptodorea\DoreaCashback\view\modals\userStatusCampaign;

/**
 * a modal to show the status of claimed cashback campaigns
 */
function userStatusCampaign():void
{
        // load claim campaign style
        wp_enqueue_style('DOREA_USERSTATUSCAMPAIGN_STYLE', plugins_url('/cryptodorea/css/userStatusCampaign.css'));

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
                           <h6 class="!mt-3">Claimed Rewards: ' . array_sum($sumUserEthers) . ' ETH </h6>
                                       
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
                           <h6 class="">You didn\'t have won any rewards yet!</h6>      
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
                   <h6>You didn\'t join any Campaigns yet!</h6>      
                </div>
            ");
        }

        // load claim campaign scripts
        wp_enqueue_script('DOREA_USERSTATUSCAMPAIGN_SCRIPT', plugins_url('/cryptodorea/js/userStatusCampaign.js'), array('jquery', 'jquery-ui-core'));
}
