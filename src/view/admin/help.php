<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
    wp_enqueue_style('DOREA_MAIN_STYLE',DOREA_PLUGIN_URL .('css/doreaHelp.css'),
        array(),
        1,
    );

    // Usage Example
    $image_set = array(
        'doreaCreateCampaign'  => 'pics/help/doreaCreateCampaign.jpeg',
        'doreaCreateCampaign2' => 'pics/help/doreaCreateCampaign2.jpeg',
        'doreaCreateCampaign3' => 'pics/help/doreaCreateCampaign3.jpeg',
        'doreaFundCampaign'       => 'pics/help/doreaFundCampaign.jpeg',
        'doreaFundCampaign2'      => 'pics/help/doreaFundCampaign2.jpeg',
        'doreaDisableEnable'      => 'pics/help/doreaDisableEnable.jpeg',
        'doreaDisableEnable2'     => 'pics/help/doreaDisableEnable2.jpeg',
        'doreaMenu'               => 'pics/help/doreaMenu.jpeg',
    );

    $img_ids = dorea_images_to_media($image_set);

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
            ".wp_get_attachment_image($img_ids['doreaCreateCampaign'], '', false, array(
                'class' => 'help-image',
                'alt'   => ucfirst(str_replace(array('doreaCreateCampaign', 'doreaFundCampaign', 'doreaDisableEnable'), '', 'doreaCreateCampaign'))
            ))."
            </div>
            
            <p class='!w-10/12 !pl-5 !mt-5'>
            2. Otherwise, you can choose the  \"Create Campaign\" option from the sidebar.
            </p>
            <div class='!flex !justify-center !items-center !mt-5 !p-5'>
            ".wp_get_attachment_image($img_ids['doreaCreateCampaign2'], '', false, array(
                'class' => 'help-image',
                'alt'   => ucfirst(str_replace(array('doreaCreateCampaign', 'doreaFundCampaign', 'doreaDisableEnable'), '', 'doreaCreateCampaign2'))
            ))."                
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
            ".wp_get_attachment_image($img_ids['doreaCreateCampaign3'], '', false, array(
                'class' => 'help-image',
                'alt'   => ucfirst(str_replace(array('doreaCreateCampaign', 'doreaFundCampaign', 'doreaDisableEnable'), '', 'doreaCreateCampaign3'))
            ))." 
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
            ".wp_get_attachment_image($img_ids['doreaFundCampaign'], '', false, array(
                'class' => 'help-image',
                'alt'   => ucfirst(str_replace(array('doreaCreateCampaign', 'doreaFundCampaign', 'doreaDisableEnable'), '', 'doreaFundCampaign'))
            ))." 
            </div>
            
            <p class='!w-10/12 !pl-5 !mt-3 !leading-7'>
            2. you should have installed Metmask Extention on your browser to fund your campaign. 
            make sure your metamask wallet address has enough ethers to fund the campaign. 
            click on the <span class='!font-bold'>\"Fund Campaign\"</span> option then confirm the metamask window and wait to deploy the campaign into the Blockchain.
            
            </p>
            <div class='!flex !justify-center !items-center !mt-5 !p-5'>
             ".wp_get_attachment_image($img_ids['doreaFundCampaign2'], '', false, array(
                'class' => 'help-image',
                'alt'   => ucfirst(str_replace(array('doreaCreateCampaign', 'doreaFundCampaign', 'doreaDisableEnable'), '', 'doreaFundCampaign2'))
            ))." 
            </div>
            
            <hr class='!w-12/12 !mt-5'>
            <h2 class='!pl-5 !mt-5 !text-sm !font-bold !mt-5'>My users want to know how much cashback they earned ?</h2> 
            <p class='!w-10/12 !pl-5 !mt-3 !leading-7'>
            Each user can access the claimed rewards number on the “My Account” page of WordPress. On that page, using the “Dorea Cashback” option, they could see the number of claimed rewards in ETH format.
            </p>
            <div class='!flex !justify-center !items-center !mt-5 !p-5'>
            ".wp_get_attachment_image($img_ids['doreaMenu'], '', false, array(
                'class' => 'help-image',
                'alt'   => ucfirst(str_replace(array('doreaCreateCampaign', 'doreaFundCampaign', 'doreaDisableEnable'), '', 'doreaMenu'))
            ))." 
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
            <span class='!font-bold'> 0x15cddCcF29A3d2653cCA38f4d752bd78171fa180 </span>
            as the service payment. This 10% guarantees the efforts of the Dorea Team to keep going on optimizing and making better the crypto Dorea plugin.
            </p>
            
            <hr class='!w-12/12 !mt-5'>
            
            <h2 class='!pl-5 !mt-5 !text-sm !font-bold !mt-5'>How to Pay ?</h2> 
            <p class='!w-10/12 !pl-5 !mt-3 !leading-7'>
            after the cashback campaign is created and users joined the campaign, 
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
             <span>
             ".wp_get_attachment_image($img_ids['doreaDisableEnable'], '', false, array(
                'class' => 'help-image !pt-3 xl:!w-52 lg:!w-52 md:!w-52 sm:!w-44 !w-40',
                'alt'   => ucfirst(str_replace(array('doreaCreateCampaign', 'doreaFundCampaign', 'doreaDisableEnable'), '', 'doreaDisableEnable'))
            ))."
             </span>
             <span>
             ".wp_get_attachment_image($img_ids['doreaDisableEnable2'], '', false, array(
                'class' => 'help-image !pt-3 xl:!w-52 lg:!w-52 md:!w-52 sm:!w-44 !w-40',
                'alt'   => ucfirst(str_replace(array('doreaCreateCampaign', 'doreaFundCampaign', 'doreaDisableEnable'), '', 'doreaDisableEnable2'))
            ))."
             </span> 
            </div>
          </div>
        </main>
    ");
}


/**
 * Handles multiple images from plugin directory to media library
 *
 * @param array $images Array of image configurations ['image_key' => 'relative/path.jpg']
 * @return array Attachment IDs (false for failed items)
 */
function dorea_images_to_media($images) {
    $stored_ids = get_option('dorea_image_attachment_ids', array());
    $upload_dir = wp_upload_dir();
    $results = array();

    foreach ($images as $key => $rel_path) {

        // Check if already processed and valid
        if (isset($stored_ids[$key]) && get_post($stored_ids[$key])) {
            $results[$key] = $stored_ids[$key];
            continue;
        }

        // Set up paths
        $plugin_full_path = DOREA_PLUGIN_DIR . 'src/view/admin/' . $rel_path;

        $target_rel_path = 'plugin-assets/' . $rel_path;
        $target_full_path = $upload_dir['basedir'] . '/' . $target_rel_path;

        // Check existing in media library
        $existing = new WP_Query(array(
            'post_type' => 'attachment',
            'posts_per_page' => 1,
            'post_status' => 'inherit',
            'meta_query' => array(array(
                'key' => '_wp_attached_file',
                'value' => $target_rel_path
            ))
        ));

        if ($existing->have_posts()) {
            $attachment_id = $existing->posts[0]->ID;
            $stored_ids[$key] = $attachment_id;
            $results[$key] = $attachment_id;
            continue;
        }

        // Validate source file
        if (!file_exists($plugin_full_path)) {
            $results[$key] = false;
            continue;
        }

        // Create target directory
        $target_dir = dirname($target_full_path);
        if (!wp_mkdir_p($target_dir)) {
            $results[$key] = false;
            continue;
        }

        // Copy file
        if (!copy($plugin_full_path, $target_full_path)) {
            $results[$key] = false;
            continue;
        }

        // Insert attachment
        $filetype = wp_check_filetype(basename($target_full_path), null);
        $attachment_args = array(
            'post_mime_type' => $filetype['type'],
            'post_title' => sanitize_file_name(pathinfo($target_full_path, PATHINFO_FILENAME)),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $attachment_id = wp_insert_attachment($attachment_args, $target_full_path);

        if (is_wp_error($attachment_id)) {
            $results[$key] = false;
            continue;
        }

        // Generate metadata
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $metadata = wp_generate_attachment_metadata($attachment_id, $target_full_path);
        wp_update_attachment_metadata($attachment_id, $metadata);

        $stored_ids[$key] = $attachment_id;
        $results[$key] = $attachment_id;
    }

    update_option('dorea_image_attachment_ids', $stored_ids);
    return $results;
}