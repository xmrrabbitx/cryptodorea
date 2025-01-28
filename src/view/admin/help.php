<?php

/**
 * help page for users
 */
function dorea_admin_help_campaign():void
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
    wp_enqueue_style('DOREA_ADMIN_STYLE',plugins_url('/cryptodorea/css/help.css'),
        array(),
        1,
        true
    );

    $createCampaignImg = plugins_url('/pics/help/createCampaign.jpeg', __FILE__);
    $createCampaignImg2 = plugins_url('/pics/help/createCampaign2.jpeg', __FILE__);
    $createCampaignImg3 = plugins_url('/pics/help/createCampaign3.jpeg', __FILE__);
    $fundCampaign = plugins_url('/pics/help/fundCampaign.jpeg', __FILE__);
    $fundCampaign2 = plugins_url('/pics/help/fundCampaign2.jpeg', __FILE__);
    $disableEnable = plugins_url('/pics/help/disableEnable.jpeg', __FILE__);
    $disableEnable2 = plugins_url('/pics/help/disableEnable2.jpeg', __FILE__);

    print("
        <main>
            <h1 class='!p-5 !text-sm !font-bold'>Help</h1> </br>
            <h2 class='!pl-5 !text-sm !font-bold'>How to Start ?</h2> </br>
            <div class='!container !pl-5 !pt-2 !pb-5 !shadow-transparent  !rounded-md'>

            <p class='!w-10/12 !pl-5 !leading-7'>
            1. If this is your first time using Crypto Dorea, 
            you can select the \"Create Your First Cashback Campaign\" option on the main page to create your first campaign.
            </p>
            <div class='!flex !justify-center !items-center !mt-5 !p-5'>
                <img class='!pt-3' src='". esc_url($createCampaignImg) ."' alt='no image!' sizes='(max-width: 50em) 87vw, 680px'>
            </div>
            
            <p class='!w-10/12 !pl-5 !mt-5'>
            2. Otherwise, you can choose the  \"Create Campaign\" option from the sidebar.
            </p>
            <div class='!flex !justify-center !items-center !mt-5 !p-5'>
                <img class='!pt-3' src='". esc_url($createCampaignImg2) ."' alt='no image!' sizes='(max-width: 50em) 87vw, 680px'>
            </div>
            
            <p class='!w-10/12 !pl-5 !mt-5  !leading-7'>
            3. in this step, you should fill in your campaign info:
            </br>
            A: <span class='!font-bold'>Campaign Name</span> _ select your desired campaign name, 
            which will show to your users on the E-Commerce Checkout Page.
            </br>
            B: <span class='!font-bold'>Amount</span> _ amount is the percentage of user purchases which is paid in ETH format. for example, 
            if user \"A\" purchased $4 and you set %10 as the amount for your campaign, it means %10 of $4 per user: $0.4 to be paid. it will be converted to ETH format: 0.00016 ETH. don't worry about ETH numbers; Dorea Cashback converts it automatically for you based on live ETH prices in the market.
            </br>
            C: <span class='!font-bold'>User Shopping Count</span> _ is the number of purchases the user must make to be eligible for cashback. for example, 
            if you set 4 as the user shopping count, 
            it means the user must purchase 4 times to be eligible for cashback, 
            the cashback amount will be calculated by the percentage of the final 4 times 
            purchases. user \"A\" purchases 4 times: $4 + $8 + $19 + $7 = $38 . 
            if you set %10 as the amount in the previous step, 
            Dorea Cashback calculates %10 of $38 to pay the user which is $3.8. 
            this amount is equal to 0.0015 ETH.
            </p>
            <div class='!flex !justify-center !items-center !mt-2 !p-5'>
                <img class='!pt-3' src='". esc_url($createCampaignImg3) ."' alt='no image!' sizes='(max-width: 50em) 87vw, 680px'>
            </div>
           
            <hr class='!w-12/12'>
           
            <h2 class='!pl-5 !mt-5 !text-sm !font-bold !mt-5'>How to Fund ?</h2> 
            <p class='!w-10/12 !pl-5 !mt-3 !leading-7'>
            1. next step is funding the campaign. 
            you can set as many as Ethers you want in this field. 0.0004 ETH is equal to $1 until this document is written. 
            for example, you could set 0.4 ETH equal to $1000 to reward your users. 
            this value changes time by time, so you should check the Ethereum price chart 
            before funding your campaign.
            </p>
            <div class='!flex !justify-center !items-center !mt-5 !p-5'>
                <img class='!pt-3' src='". esc_url($fundCampaign) ."' alt='no image!' sizes='(max-width: 50em) 87vw, 680px'>
            </div>
            
            <p class='!w-10/12 !pl-5 !mt-3 !leading-7'>
            2. you should have installed Metmask Extention on your browser to fund your campaign. 
            make sure your metamask wallet address has enough ethers to fund the campaign. 
            click on the <span class='!font-bold'>\"Fund Campaign\"</span> option then confirm the metamask window and wait to deploy the campaign into the Blockchain.
            
            </p>
            <div class='!flex !justify-center !items-center !mt-5 !p-5'>
                <img class='!pt-3 w-8/12' src='". esc_url($fundCampaign2) ."' alt='no image!' >
            </div>
            
            <hr class='!w-12/12 !mt-5'>
            <h2 class='!pl-5 !mt-5 !text-sm !font-bold !mt-5'>Is it necessary to have installed WooCommerce ?</h2> 
            <p class='!w-10/12 !pl-5 !mt-3 !leading-7'>
            Yes, WooCommerce is a prerequisite to the Cryptodorea plugin. Your woo-commerce purchases will be processed and monitored to pay the most loyal users.
            </p>
            
            <hr class='!w-12/12 !mt-5'>
            
            <h2 class='!pl-5 !mt-5 !text-sm !font-bold !mt-5'>How is the funding cashback campaign calculated ?</h2> 
            <p class='!w-10/12 !pl-5 !mt-3 !leading-7'>
            When you fund a campaign, you send it to the Ethereum blockchain. Besides the amount of money you send to the campaign, 
            a small amount of fee (regularly less than $1) pays for the blockchain. Also, 10% of the campaign amount will be calculated and sent to Dorea's Account Address: 
            <span class='!font-bold'> 0xca578e925551aCB0d86D3557a6fF26a68034C88b </span>
            as the service payment. This 10% guarantees the efforts of the Dorea Team to keep going on optimizing and making better the crypto Dorea plugin.
            </p>
            
            <hr class='!w-12/12 !mt-5'>
            
            <h2 class='!pl-5 !mt-5 !text-sm !font-bold !mt-5'>How to Pay ?</h2> 
            <p class='!w-10/12 !pl-5 !mt-3 !leading-7'>
            1. after the cashback campaign is created and users joined the campaign, 
            you can pay ethers to users. choose <span class='!font-bold'>\"Pay\"</span> option on the main page in each campaign section. 
            you should see the payment page now. On that page, you could pay campaign users.
            </p>
            
            <hr class='!w-12/12 !mt-5'>
            
            <h2 class='!pl-5 !mt-5 !text-sm !font-bold !mt-5'>What if Campaign Balance is not enough ?</h2> 
            <p class='!w-10/12 !pl-5 !mt-3 !leading-7'>
            you could fund the campaign again on the payment page. 
            after that <span class='!font-bold'>\"Pay Campaign\" </span> option appears to pay users.
            </p>
            
            <hr class='!w-12/12 !mt-5'>
            
            <h2 class='!pl-5 !mt-5 !text-sm !font-bold !mt-5'>How can i see transaction list of campaigns ?</h2> 
            <p class='!w-10/12 !pl-5 !mt-3 !leading-7'>
            on the main page, in each section of the campaign, there is a transactions list icon on the right side. click on that icon, 
            you should see paid details like the user's wallet address and paid ethers amount.
            </p>
            
            <hr class='!w-12/12 !mt-5'>
            
            <h2 class='!pl-5 !mt-5 !text-sm !font-bold !mt-5'>What if i want to disable campaign ?</h2> 
            <p class='!w-10/12 !pl-5 !mt-3 !leading-7'>
            you can toggle between disable/enable options in each campaign. 
            the disabling of the campaign doesn't count the user's purchases that 
            participate in the campaign.            
            </p>
            
            <div class='!flex !justify-center !items-center !mt-5 !p-5'>
                <img class='!pt-3 xl:!w-52 lg:!w-52 md:!w-52 sm:!w-44 !w-40' src='". esc_url($disableEnable) ."' alt='no image!'>
                <img class='!pt-3 xl:!w-52 lg:!w-52 md:!w-52 sm:!w-44 !w-40' src='". esc_url($disableEnable2) ."' alt='no image!'>
            </div>
            
            
          </div>
        </main>
    ");
}