<?php


use Cryptodorea\DoreaCashback\controllers\cashbackController;
use Cryptodorea\DoreaCashback\utilities\dateCalculator;

// check nonce validation
add_action('check_admin_referer', 'dorea_referer_check',10,2);
function dorea_referer_check($action, $result)
{
    if (!$result) {
       // error_log("Invalid nonce for action: $action");
    }
    return $action;
}

/**
 * Crypto Cashback Campaign
 */
function dorea_cashback_campaign_content():void
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

    // load campaign css styles
    wp_enqueue_style('DOREA_CAMPAIGN_STYLE',plugins_url('/cryptodorea/css/campaign.css'),
        array(),
        1,
    );

    // load campaign scripts
    wp_enqueue_script('DOREA_CAMPAIGN_SCRIPT',plugins_url('/cryptodorea/js/campaign.js'), array('jquery', 'jquery-ui-core'),
        array(),
        1,
        true
    );

    // utilities helper functions
    $dateCalculator = new dateCalculator();
    $currentTime = $dateCalculator->currentDate();
    $currentDate = $dateCalculator->unixToMonth($currentTime);
    $currentYear = $dateCalculator->unixToYear($currentTime);

    $currentDay = (int) $dateCalculator->unixToDay($currentTime);

    $monthsList = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $daysList = ['January'=>31, 'February'=>29, 'March'=>31, 'April'=>30, 'May'=>31, 'June'=>30, 'July'=>31, 'August'=>31, 'September'=>30, 'October'=>31, 'November'=>30, 'December'=>31];

    print("<main>");
    print("<h1 class='!p-5 !text-sm !font-bold'>Create Campaign</h1>");

    print("
        <p class='!pl-5 !col-span-12 h-3 w-full'>
        <p class='!pl-5' id='errorMessg' style='display: none'></p>
    ");

    $credit_url = wp_nonce_url(esc_url(admin_url('admin-post.php')));
    print("
      </p>
      <div class='!container !mx-auto !pl-5 !pt-2 !pb-5 !shadow-transparent !text-center !rounded-md'>
        
        <h2 class='!text-center !text-lg !divide-y !mt-5'>Crypto Dorea Cashback</h2>
        <hr class='border-1 !w-64 !text-center !dark:bg-gray-700 !w-48 1h-1 !mx-auto !mt-2'>

        <form class='!grid !grid-cols-1 !mt-5' method='POST' action='".esc_url($credit_url)."' id='cashback_campaign'>
            
            <input type='hidden' name='action' value='cashback_campaign'>
           
           <!-- campaign name field -->
           <div class='!col-span-1 !w-12/12'>
                <input id='campaignName' class='!rounded-md !w-64 !p-2 !focus:ring-green-500 !border-hidden !focus:ring-0 !focus:outline-none !outline-none!bg-white' type='text' name='campaignName' placeholder='campaign name'>
           </div>
    ");

    print("
            <!-- crypto type options -->
            <div class='!col-span-1 !w-12/12 !mt-5 hidden'>
               <lable class='!pr-3'>crypto type</lable>
               <select class='!border-hidden' name='cryptoType'>
                  <option selected value='eth'>Ethereum</option>
               </select>
            </div>
            <div class='!col-span-1 !w-12/12 !mt-3'>
                <!-- amount options -->
                <input id='cryptoAmount' class='!border-hidden !w-64 !mt-3 !p-2' type='text' name='cryptoAmount' placeholder='% amount'>
           </div>
    ");

    print("
            <div class='!col-span-1 !w-12/12 !mt-3'>
                <!-- Shopping Counts options -->
                <input id='shoppingCount' class='!border-hidden !w-64 !mt-2 !p-2' type='text' name='shoppingCount' placeholder='user shopping count'>
            </div>
            
            <div class='!col-span-1 !w-12/12 !mt-5'>
                <div class='!w-64 !bg-white !rounded-md !p-2 !mx-auto'>
                
                    <!-- start date options -->
                  <lable class='!float-left !pt-1 !pr-3 !text-[14px]'>Start Date</lable>
                  <div class='!flex !flex-grid !gap-1'>
                    <select class='!text-right !border-hidden' name='startDateMonth' id='startDateMonth'>
                    
    ");

            $index = array_search($currentDate,$monthsList);
            $nextYear = $currentYear;
            foreach($monthsList as $month){

                // fix this part
                if($index > (count($monthsList)-1)){
                    $index = 0;
                    $nextYear = (int)$currentYear + 1;
                }


                if($index < 9){
                    $monthNum = '0' . $index + 1;
                }else {
                    $monthNum = $index + 1;
                }

                if($monthsList[$index] === $currentDate){
                    print("<option class='!border-hidden' value='".esc_js($monthNum) . "_" . esc_js($nextYear)."' selected>".esc_html($monthsList[$index])."</option>");

                }
                else{
                    print("<option class='!border-hidden' value='".esc_js($monthNum) . "_" . esc_js($nextYear)."'>".esc_html($monthsList[$index])."</option>");

                }

                $index += 1;


            }

            print("
                    </select>
                    <select class='!border-hidden' name='startDateDay' id='startDateDay'>");

            $index=0;
            for($days=1;$days <= $daysList[$currentDate]; $days++){

                if($index < 9){
                    $index = '0' . $index + 1;
                }else {
                    $index = $index + 1;
                }

                if($days === $currentDay){
                    print("
                      <option class='!border-hidden' value='".esc_js($index)."' selected>".esc_html($days)."</option>
                    ");
                }else {
                    print("
                      <option class='!border-hidden' value='".esc_js($index)."'>" . esc_html($days) . "</option>
                    ");
                }
            }
            
            print("
                      </select>
                      </div>
                    </div>
                </div>
                <div class='!col-span-1 !w-12/12 !mt-5 text-center'>
                <div class='!w-64 !bg-white !rounded-md !p-2 !mx-auto'>
                    <!-- expire date options -->
                    <lable class='!float-left !pt-1 !text-[14px]'>Expiration Date</lable>
                    <select class='!border-hidden' name='expDate' id='expDate'>
                        <option value='weekly'>Weekly</option>
                        <option value='monthly'>Monthly</option>
            ");

            print("
                            </select>
                          </div>
                        </div>
                    <div class='!col-span-1 !w-12/12 !mt-5'>
                        <button id='setupCampaign' class='!p-3 !w-64 !bg-[#faca43] !rounded-md' type='submit'>set up campaign</button>
                    </div>
                </form>
                </br>
              </div>
            ");

    print("</main>");
}

/**
 * set up cashback campaign
 */
add_action('admin_post_cashback_campaign', 'dorea_admin_cashback_campaign');
function dorea_admin_cashback_campaign():void
{
    // check nonce validation
    check_admin_referer();

    $referer = explode("&", wp_get_referer())[0];

    if(!empty($_POST['campaignName'] && $_POST['cryptoType'] && $_POST['cryptoAmount'] && $_POST['shoppingCount'] && $_POST['startDateMonth'] && $_POST['startDateDay'] && $_POST['expDate'])){

            $campaignNameLable = trim(sanitize_text_field(wp_unslash($_POST['campaignName'])));
            $campaignNameLable = preg_replace("/[^A-Za-z0-9 ]/", '', $campaignNameLable);
            if(strlen($campaignNameLable) >= 25){
                wp_redirect($referer);
                exit;
            }
            $campaignName = trim(sanitize_text_field(sanitize_key(wp_unslash($_POST['campaignName']))));
            $cryptoType = htmlspecialchars(sanitize_text_field(wp_unslash($_POST['cryptoType'])));

            if(!is_numeric(trim(sanitize_text_field(wp_unslash($_POST['cryptoAmount'])))) || !is_numeric(trim(sanitize_text_field(wp_unslash($_POST['shoppingCount']))))){
                //throws error on amount format
                $redirect_url = add_query_arg('cryptoAmountError', urlencode('amount and shopping counts must be numeric!'), $referer);

                wp_redirect($redirect_url);
                exit;
            }

            $cryptoAmount = (int)htmlspecialchars(sanitize_text_field(wp_unslash($_POST['cryptoAmount'])));
            $shoppingCount = (int)htmlspecialchars(sanitize_text_field(wp_unslash($_POST['shoppingCount'])));

            $startDate = htmlspecialchars(sanitize_text_field(wp_unslash($_POST['startDateMonth'])));
            $startDateDay = htmlspecialchars(sanitize_text_field(wp_unslash($_POST['startDateDay'])));
            $startDateMonth = explode('_',$startDate)[0];
            $startDateYear = explode('_',$startDate)[1];

            $expMode = htmlspecialchars(sanitize_text_field(wp_unslash($_POST['expDate'])));

            $timestampStart = strtotime($startDateDay . '.' . $startDateMonth . '.' . $startDateYear . " 00:00:00");

            if($expMode === "weekly") {
                $timestampExpire = $timestampStart + 604800; // calculate next 7 days of timestamp
            }elseif($expMode === "monthly") {
                $timestampExpire = $timestampStart + 2592000; // calculate next 1 month of timestamp
            }

            // add random hash to campaign name
            $campaignName = $campaignName . "_" . substr(md5(openssl_random_pseudo_bytes(20)),-7);

            $cashback = new cashbackController();
            if(get_option('campaign_list')){
                //throws error on existed campaign
                if(in_array($campaignName, get_option('campaign_list'))){
                    $redirect_url = add_query_arg('existedCampaignError', urlencode('Campaign is already existed!'), $referer);
                    wp_redirect($redirect_url);
                    exit;
                }
            }
            $campaignLength = explode("_",$campaignName)[0];
            if(strlen($campaignLength) >= 25 ){
                //throws error on character exceed!
                $redirect_url = add_query_arg('campaignError', urlencode('no more than 25 characters allowed on campaign name!'), $referer);
                wp_redirect($redirect_url);

            }else {

                if (empty(get_option('campaign_list')) || get_option('campaign_list') === NULL) {

                    delete_option('campaign_list');

                }

                // create campaign
                $cashback->create($campaignName, $campaignNameLable, $cryptoType, $cryptoAmount, $shoppingCount,$timestampStart, $timestampExpire);

                $url = 'admin.php?page=credit&cashbackName=' . $campaignName;
                $nonce = wp_create_nonce("deploy_campaign_nonce");
                $url = $url . "&_wpnonce=" . $nonce;

                // head to the admin page credit
                wp_redirect($url);
                exit;
            }

    }else{
        //throws error on empty
        $redirect_url = add_query_arg('emptyErrorFeilds', urlencode('some fields left empty!'), $referer);

        wp_redirect($redirect_url);
        exit;

    }
    
}

/**
 * delete a campaign
 */
add_action('admin_post_delete_campaign', 'dorea_admin_delete_campaign');
function dorea_admin_delete_campaign():void
{
    if(isset($_GET['_wpnonce'])){
        $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
        if (wp_verify_nonce($nonce, 'delete_campaign_nonce') ) {

            // Get campaign name
            $campaignName = isset($_GET['cashbackName']) ? sanitize_text_field(wp_unslash($_GET['cashbackName'])) : '';

            // Perform your delete operation here
            $cashback = new cashbackController();
            $cashback->remove($campaignName);

            // Redirect back to the previous page after deletion
            wp_redirect(wp_get_referer());
            exit;
        }
    }
}