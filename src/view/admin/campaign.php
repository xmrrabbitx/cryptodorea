<?php

/**
 * Crypto Cashback Campaign
 */

use Cryptodorea\Woocryptodorea\controllers\cashbackController;
use Cryptodorea\Woocryptodorea\utilities\dateCalculator;

function dorea_cashback_campaign_content():void
{
    // load campaign css styles
    wp_enqueue_style('DOREA_CAMPAIGN_STYLE',plugins_url('/woo-cryptodorea/css/campaign.css'));

    // load campaign scripts
    wp_enqueue_script('DOREA_CAMPAIGN_SCRIPT',plugins_url('/woo-cryptodorea/js/campaign.js'), array('jquery', 'jquery-ui-core'));

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
        <p id='errorMessg' style='display: none'></p>
    ");

    print("
      </p>
      <div class='!container !mx-auto !pl-5 !pt-2 !pb-5 !shadow-transparent !text-center !rounded-md'>
        
        <h2 class='!text-center !text-lg !divide-y !mt-5'>Crypto Dorea Cashback</h2>
        <hr class='border-1 !w-64 !text-center !dark:bg-gray-700 !w-48 1h-1 !mx-auto !mt-2'>

        <form class='!grid !grid-cols-1 !mt-5' method='POST' action='".esc_url(admin_url('admin-post.php'))."' id='cashback_campaign'>
            
            <input type='hidden' name='action' value='cashback_campaign'>
           
           <!-- campaign name field -->
           <div class='!col-span-1 !w-12/12'>
                <input id='campaignName' class='!rounded-md !w-64 !p-2 !focus:ring-green-500 !border-hidden !focus:ring-0 !focus:outline-none !outline-none!bg-white' type='text' name='campaignName'  placeholder='campaign name'>
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
                <input id='cryptoAmount' class='!border-hidden !w-64 !mt-3 !p-2' type='text' name='cryptoAmount' placeholder='amount'>
           </div>
    ");

    print("
            <div class='!col-span-1 !w-12/12 !mt-3'>
                <!-- Shopping Counts options -->
                <input id='shoppingCount' class='!border-hidden !w-64 !mt-2 !p-2' type='text' name='shoppingCount' placeholder='Shopping Counts'>
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
                    <lable class='!float-left !pt-1 !text-[14px]'>Expire Date</lable>
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
function dorea_admin_cashback_campaign()
{
    $referer = explode("&", wp_get_referer())[0];

    if(!empty($_POST['campaignName'] && $_POST['cryptoType'] && $_POST['cryptoAmount'] && $_POST['shoppingCount'] && $_POST['startDateMonth'] && $_POST['startDateDay'] && $_POST['expDate'])){

            $campaignName = trim(htmlspecialchars($_POST['campaignName']));
            $cryptoType = htmlspecialchars($_POST['cryptoType']);

            if(!is_numeric(trim($_POST['cryptoAmount'])) || !is_numeric(trim($_POST['shoppingCount']))){

                //throws error on amount format
                $redirect_url = add_query_arg('cryptoAmountError', urlencode('amount and shopping counts must be numeric!'), $referer);

                wp_redirect($redirect_url);
                return false;

            }

            $cryptoAmount = (float)htmlspecialchars($_POST['cryptoAmount']);
            $shoppingCount = (int)htmlspecialchars($_POST['shoppingCount']);
            $startDate = htmlspecialchars($_POST['startDateMonth']);
            $startDateMonth = explode('_',$startDate)[0];
            $startDateYear = explode('_',$startDate)[1];
            $startDateDay = htmlspecialchars($_POST['startDateDay']);

            $timestampStart = strtotime($startDateDay . '.' . $startDateMonth . '.' . $startDateYear . " 00:00:00");
            $timestampExpire = $timestampStart + 691199; // calculate next 7 days of timestamp

            // add random hash to campaign name
            $campaignName = $campaignName . "_" . substr(md5(openssl_random_pseudo_bytes(20)),-7);

            $cashback = new cashbackController();
            if(get_option('campaign_list')){

                //throws error on existed campaign
                if(in_array($campaignName, get_option('campaign_list'))){

                    $redirect_url = add_query_arg('existedCampaignError', urlencode('Campaign is already existed!'), $referer);
                    wp_redirect($redirect_url);
                    return false;

                }

            }

            if(strlen($campaignName) > 25 ){

                //throws error on character exceed!
                $redirect_url = add_query_arg('campaignError', urlencode('no more than 25 characters allowed!'), $referer);
                wp_redirect($redirect_url);

            }else {

                if (empty(get_option('campaign_list')) || get_option('campaign_list') === NULL) {

                    delete_option('campaign_list');

                }

                // create campaign
                $cashback->create($campaignName, $cryptoType, $cryptoAmount, $shoppingCount,$timestampStart, $timestampExpire);

                // head to the admin page of Dorea
                wp_redirect('admin.php?page=credit&cashbackName=' . $campaignName);

            }

    }else{

        //throws error on empty
        $redirect_url = add_query_arg('emptyErrorFeilds', urlencode('some fields left empty!'), $referer);

        wp_redirect($redirect_url);
        return false;

    }
    
}

/**
 * delete a campaign
 */
add_action('admin_post_delete_campaign', 'dorea_admin_delete_campaign');

function dorea_admin_delete_campaign():void
{

    if ( !isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'delete_campaign_nonce') ) {
        die('Security check failed');
    }

    // Get campaign name
    $campaignName = isset($_GET['cashbackName']) ? sanitize_text_field($_GET['cashbackName']) : '';

    // Perform your delete operation here
    $cashback = new cashbackController();
    $cashback->remove($campaignName);
    
    // Redirect back to the previous page after deletion
    wp_redirect(wp_get_referer());
    exit;

}