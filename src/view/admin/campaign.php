<?php

/**
 * Crypto Cashback Campaign
 */

use Cryptodorea\Woocryptodorea\controllers\cashbackController;
use Cryptodorea\Woocryptodorea\controllers\autoremoveController;
use Cryptodorea\Woocryptodorea\utilities\dateCalculator;

function dorea_cashback_campaign_content(){


    print('
        <style>
            body{
                background: #f6f6f6;
            }
            main{
                font-family: "Poppins", sans-serif !important;
            }
        </style>
    ');

    // utilities helper functions
    $dateCalculator = new dateCalculator();
    $currentTime = $dateCalculator->currentDate();
    $currentDate = $dateCalculator->unixToMonth($currentTime);
    $currentYear = $dateCalculator->unixToYear($currentTime);

    //remove  after test
    //$currentDate = "January";

    $currentDay = (int) $dateCalculator->unixToDay($currentTime);
    //$futureDate = $dateCalculator->futureDate(7,1,2022);
    //$futureDate = $dateCalculator->unixToMonth($futureDate);

    $monthsList = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $daysList = ['January'=>31, 'February'=>29, 'March'=>31, 'April'=>30, 'May'=>31, 'June'=>30, 'July'=>31, 'August'=>31, 'September'=>30, 'October'=>31, 'November'=>30, 'December'=>31];

    print("<main>");
    print("<h1 class='p-5 text-sm font-bold'>Create Campaign</h1> </br>");
    $emptyError = filter_input( INPUT_GET, 'emptyErrorFeilds' );
    if($emptyError){
        print("<span style='color:#ff5d5d;'>$emptyError</span>");
    }
    print("
      <div class='container mx-auto pl-5 pt-2 pb-5 shadow-transparent text-center rounded-md'>
        
        <h2 class='!text-center !text-lg !divide-y !mt-5'>Crypto Dorea Cashback</h2>
        <hr class='border-1 !w-64 !text-center !dark:bg-gray-700 !w-48 1h-1 !mx-auto !mt-2'>

        <form class='!grid !grid-cols-1 !mt-5' method='POST' action='".esc_url(admin_url('admin-post.php'))."' id='cashback_campaign'>
            
            <input type='hidden' name='action' value='cashback_campaign'>
           
           <!-- campaign name field -->
           <div class='!col-span-1 !w-12/12'>
                <input class='!rounded-md !w-64 !p-2 !focus:ring-green-500 !border-hidden !bg-white' type='text' name='campaignName'  placeholder='campaign name'>
           </div>
    ");
            $campaignError = filter_input( INPUT_GET, 'campaignError' );
            if($campaignError){
                print("<span style='color:#ff5d5d;'>$campaignError</span>");
            }
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
                <input class='!border-hidden !w-64 !mt-3 !p-2' type='text' name='cryptoAmount' placeholder='amount'>
           </div>
    ");
    $cryptoAmount = filter_input( INPUT_GET, 'cryptoAmountError' );
    if($cryptoAmount){
        print("<span style='color:#ff5d5d;'>$cryptoAmount</span>");
    }
    print("
            <div class='!col-span-1 !w-12/12 !mt-3'>
                <!-- Shopping Counts options -->
                <input class='!border-hidden !w-64 !mt-2 !p-2' type='text' name='shoppingCount' placeholder='Shopping Counts'>
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
                    print("<option class='!border-hidden' value='".$monthNum . "_" . $nextYear."' selected>".$monthsList[$index]."</option>");

                }
                else{
                    print("<option class='!border-hidden' value='".$monthNum . "_" . $nextYear."'>".$monthsList[$index]."</option>");

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
                      <option class='!border-hidden' value='".$index."' selected>".$days."</option>
                    ");
                }else {
                    print("
                      <option class='!border-hidden' value='".$index."'>" . $days . "</option>
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
                <button class='!p-3 !w-64 !bg-[#faca43] !rounded-md' type='submit'>set up campaign</button>
            </div>
        </form>
        </br>
      </div>
    
        ");

    $expiredError = filter_input( INPUT_GET, 'expiredError' );
    if($expiredError){
        print("<span style='color:#ff5d5d;'>$expiredError</span>");
    }

    $existedCampaignError = filter_input( INPUT_GET, 'existedCampaignError' );
    if($existedCampaignError){
        print("<span style='color:#ff5d5d;'>$existedCampaignError</span>");
    }

    print("</main>");
}

/**
 * set up cashback campaign
 */
add_action('admin_post_cashback_campaign', 'dorea_admin_cashback_campaign');

function dorea_admin_cashback_campaign(){


    //static $home_url = 'admin.php?page=crypto-dorea-cashback';
    $referer = wp_get_referer();

    if(!empty($_POST['campaignName'] && $_POST['cryptoType'] && $_POST['startDateMonth'] && $_POST['startDateDay'] && $_POST['expDate'])){
       
            $campaignName = trim(htmlspecialchars($_POST['campaignName']));
            $cryptoType = htmlspecialchars($_POST['cryptoType']);

            if(!is_numeric(trim($_POST['cryptoAmount']))){

                //throws error on amount format
                $redirect_url = add_query_arg('cryptoAmountError', urlencode('Error: Amount must be numeric!'), $referer);

                wp_redirect($redirect_url);
                return false;

            }

            $cryptoAmount = (float)htmlspecialchars($_POST['cryptoAmount']);
            $shoppingCount = (int)htmlspecialchars($_POST['shoppingCount']);
            $startDate = htmlspecialchars($_POST['startDateMonth']);
            $startDateMonth = explode('_',$startDate)[0];
            $startDateYear = explode('_',$startDate)[1];

            $startDateDay = htmlspecialchars($_POST['startDateDay']);
            $expDate = htmlspecialchars($_POST['expDate']);

            $dateCalculator = new dateCalculator();
            $expDate = $dateCalculator->expDateCampaign($startDateDay, $startDateMonth,$startDateYear, $expDate);

            $timestampDate = strtotime($expDate['expDay'] . '.' . $expDate['expMonth'] . '.' . $expDate['expYear']);

            // add random hash to campaign name
            $campaignName = $campaignName . "_" . substr(md5(openssl_random_pseudo_bytes(20)),-7);

            $cashback = new cashbackController();
            if(get_option('campaign_list')){

                //throws error on existed campaign
                if(in_array($campaignName, get_option('campaign_list'))){

                    $redirect_url = add_query_arg('existedCampaignError', urlencode('Error: Campaign is already existed!'), $referer);

                    wp_redirect($redirect_url);
                    return false;

                }

            }

            if(strlen($campaignName) > 25 ){

                //throws error on character exceed!
                $redirect_url = add_query_arg('campaignError', urlencode('Error: no more than 25 characters allowed!'), $referer);

                wp_redirect($redirect_url);

            }else {

                if (empty(get_option('campaign_list')) || get_option('campaign_list') === NULL) {

                    delete_option('campaign_list');

                }

                // create campaign
                $cashback->create($campaignName, $cryptoType, $cryptoAmount, $shoppingCount,$startDateYear, $startDateMonth, $startDateDay, $expDate['expMonth'], $expDate['expDay'], $timestampDate);

                // check error on expired campaign
                if(dorea_autoremove_campaign_admin() === true){

                    //throws error on date format
                    $redirect_url = add_query_arg('expiredError', urlencode('Error: campaign date is not valid!'), $referer);

                    wp_redirect($redirect_url);
                    return false;
                }

                // head to the admin page of Dorea
                wp_redirect('admin.php?page=credit&cashbackName=' . $campaignName);

            }

    }else{

        //throws error on empty
        $redirect_url = add_query_arg('emptyErrorFeilds', urlencode('Error: some feilds left empty!'), $referer);

        wp_redirect($redirect_url);
        return false;

    }
    
}



/**
 * delete a campaign
 */
add_action('admin_post_delete_campaign', 'dorea_admin_delete_campaign');

function dorea_admin_delete_campaign(){

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

/**
 * auto remove outdated campaign trigger on website
 */
add_action('wp', 'dorea_autoremove_campaign');
function dorea_autoremove_campaign()
{

    $campaignName = get_option('campaign_list');
    if(isset($campaignName)) {
        $autoremove = new autoremoveController();
        $autoremove->remove($campaignName);
    }
}


/**
 * auto remove outdated campaign trigger in admin menu
 */
add_action('admin_menu', 'dorea_autoremove_campaign_admin');
function dorea_autoremove_campaign_admin()
{

    $campaignName = get_option('campaign_list');
    if(isset($campaignName)) {
        $autoremove = new autoremoveController();
        return $autoremove->remove($campaignName);
    }

}
