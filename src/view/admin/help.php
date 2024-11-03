<?php

/**
 * help page for users
 */
function dorea_admin_help_campaign():void
{
    // check nonce validation
    check_admin_referer();

    // load admin css styles
    wp_enqueue_style('DOREA_ADMIN_STYLE',plugins_url('/cryptodorea/css/help.css'));

    $createCampaignImg = plugins_url('/pics/help/createCampaign.jpeg', __FILE__);
    $createCampaignImg2 = plugins_url('/pics/help/createCampaign2.jpeg', __FILE__);
    $createCampaignImg3 = plugins_url('/pics/help/createCampaign3.jpeg', __FILE__);
    $fundCampaign = plugins_url('/pics/help/fundCampaign.jpeg', __FILE__);
    $fundCampaign2 = plugins_url('/pics/help/fundCampaign2.jpeg', __FILE__);

    print("
        <main>
          <div class='!container !pl-5 !pt-2 !pb-5 !shadow-transparent  !rounded-md'>
            <h1 class='!p-5 !text-sm !font-bold'>Help</h1> </br>
            
            <h2 class='!pl-5 !text-sm !font-bold'>How to Start?</h2> </br>
            <p class='!w-10/12 !pl-5 !leading-7'>
            1. if it is your first time to use Crypto Dorea, 
            you can select \"Create Your First Cashback Campaign\"  option in first page to create your first campaign.
            </p>
            <div class='!flex !justify-center !items-center !mt-5 !p-5'>
                <img class='!pt-3' src='". esc_url($createCampaignImg) ."' alt='no image!' sizes='(max-width: 50em) 87vw, 680px'>
            </div>
            
            <hr class='!w-12/12'>
            
            <p class='!w-10/12 !pl-5 !mt-5'>
            2. otherwise, you could choose \"Create Campaign\" option from the sidebar.
            </p>
            <div class='!flex !justify-center !items-center !mt-5 !p-5'>
                <img class='!pt-3' src='". esc_url($createCampaignImg2) ."' alt='no image!' sizes='(max-width: 50em) 87vw, 680px'>
            </div>
            
            <hr class='!w-12/12'>
            
            <p class='!w-10/12 !pl-5 !mt-5  !leading-7'>
            3. in this step, you should fill in your campaign info:
            </br>
            A: <span class='!font-bold'>Campaign Name</span> _ choose your desired campaign name, 
            this name will show to your users in E-Commerce Checkout Page.
            </br>
            B: <span class='!font-bold'>Amount</span> _ amount is the percentage of user purchases which is paid in ETH format.
            for example, user \"A\" purchased $4, you set %10 as amount for your campaign, which means %10 of $4 per user: $0.4 to be paid. it will be converted to ETH format: 0.00016 ETH.
            don't wory about ETH numbers, Dorea Cashback convert it automatically for you.  
            </br>
            C: <span class='!font-bold'>Shopping Counts</span> _ is the numbers of purchases user must do it to be eligible for cashback. 
            for example, if you set 4 value as shopping counts, it means user must purchases 4 times to be eligible for cashback, 
            the cashback amount will be calculated by percentage of final 4 times purchases.
            user \"A\" purchases 4 times: $4 + $8 + $19 + $7 = $38 . if you set %10 as amount in previous step, dorea cashback calculates %10 of $38 to pay user which is $3.8. 
            this amount is equal to 0.0015 ETH.
            </p>
            <div class='!flex !justify-center !items-center !mt-2 !p-5'>
                <img class='!pt-3' src='". esc_url($createCampaignImg3) ."' alt='no image!' sizes='(max-width: 50em) 87vw, 680px'>
            </div>
           
            <h2 class='!pl-5 !mt-5 !text-sm !font-bold'>How to Fund?</h2> 
            <p class='!w-10/12 !pl-5 !mt-3 !leading-7'>
            1. next step is funding campaign. you can set Ethers as many as you want in this field. 
            0.0004 ETH is equal to $1 until this document is written. for example, you could set 0.4 ETH equals to $1000 to reward your users. 
            this value changes time by time, so you should check ethereum price chart before to fund your campaign.
            </p>
            <div class='!flex !justify-center !items-center !mt-5 !p-5'>
                <img class='!pt-3' src='". esc_url($fundCampaign) ."' alt='no image!' sizes='(max-width: 50em) 87vw, 680px'>
            </div>
            
            <p class='!w-10/12 !pl-5 !mt-3 !leading-7'>
            2. you should have installed Metmask Extention on your browser to fund your campaign. 
            make sure your metamask wallet address have enough ethers to fund the campaign. 
            click on \"Fund Campaign\" option then confirm metamask window and wait to deploy the campaign into the Blockchain,
            </p>
            <div class='!flex !justify-center !items-center !mt-5 !p-5'>
                <img class='!pt-3 w-8/12' src='". esc_url($fundCampaign2) ."' alt='no image!' >
            </div>
            
          </div>
        </main>
    ");



}