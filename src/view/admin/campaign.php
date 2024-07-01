<?php

/**
 * Crypto Cashback Campaign
 */

use Cryptodorea\Woocryptodorea\controllers\cashbackController;
use Cryptodorea\Woocryptodorea\controllers\autoremoveController;
use Cryptodorea\Woocryptodorea\utilities\dateCalculator;


function dorea_cashback_campaign_content(){

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

    print("campaign page");
    $emptyError = filter_input( INPUT_GET, 'emptyErrorFeilds' );
    if($emptyError){
        print("<span style='color:#ff5d5d;'>$emptyError</span>");
    }
    print("
        </br>
        <form method='POST' action='".esc_url(admin_url('admin-post.php'))."' id='cashback_campaign'>
            
            <input type='hidden' name='action' value='cashback_campaign'>
           
            <lable>campaign name</lable>
            <input type='text' name='campaignName'>
    ");
            $campaignError = filter_input( INPUT_GET, 'campaignError' );
            if($campaignError){
                print("<span style='color:#ff5d5d;'>$campaignError</span>");
            }
    print("
            </br>

            <lable>crypto type</lable>
            <select name='cryptoType'>
                <option selected value='eth'>Ethereum</option>
            </select>
            </br>

            <lable>amount</lable>
            <input type='text' name='cryptoAmount'>
           
    ");
    $cryptoAmount = filter_input( INPUT_GET, 'cryptoAmountError' );
    if($cryptoAmount){
        print("<span style='color:#ff5d5d;'>$cryptoAmount</span>");
    }
    print("
            </br>
            <lable>Shopping Counts</lable>
            <input type='text' name='shoppingCount'>
            </br>
            
            <lable>start date</lable>
            <select name='startDateMonth' id='startDateMonth'>
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
                    print("<option value='".$monthNum . "_" . $nextYear."' selected>".$monthsList[$index]."</option>");

                }
                else{
                    print("<option value='".$monthNum . "_" . $nextYear."'>".$monthsList[$index]."</option>");

                }

                $index += 1;


            }

            print("</select>
                    <span>
                    <select name='startDateDay' id='startDateDay'>");

            $index=0;
            for($days=1;$days <= $daysList[$currentDate]; $days++){

                if($index < 9){
                    $index = '0' . $index + 1;
                }else {
                    $index = $index + 1;
                }

                if($days === $currentDay){
                    print("
                    <option value='".$index."' selected>".$days."</option>
                    ");
                }else {
                    print("
                    <option value='".$index."'>" . $days . "</option>
                    ");
                }
            }
            
            print("</select>
                </span>
                </br>
    
                <lable>expire date</lable>
                <select name='expDate' id='expDate'>
                 <option value='weekly'>Weekly</option>
                 <option value='monthly'>Monthly</option>
            ");

            print("</select>
                    </span>
                    </br>

            <button type='submit'>set up campaign!</button>
        
        </form>
        </br>
    
        ");
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

                //throws error on not existed campaign
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

            $cashback = new cashbackController();
            if(get_option('campaign_list')){
                if(array_search($campaignName, get_option('campaign_list'))){

                    //throws error on not existed campaign
                    $redirect_url = add_query_arg('campaignError', urlencode('Error: Campaign is already exists!'), $referer);

                    wp_redirect($redirect_url);
                    return false;
                }
            }

            if(strlen($campaignName) > 25 ){

                //throws error on character exceed!
                $redirect_url = add_query_arg('campaignError', urlencode('Error: no more than 25 characters allowed!'), $referer);

                wp_redirect($redirect_url);

            }


            else {

                if (empty(get_option('campaign_list')) || get_option('campaign_list') === NULL) {

                    delete_option('campaign_list');

                }

                $cashback->create($campaignName, $cryptoType, $cryptoAmount, $shoppingCount,$startDateYear, $startDateMonth, $startDateDay, $expDate['expMonth'], $expDate['expDay'], $timestampDate);

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
 * auto remove outdated campaign
 */
add_action('wp', 'dorea_autoremove_campaign');
function dorea_autoremove_campaign()
{

    $campaignName = get_option('campaign_list');

    $autoremove = new autoremoveController();
    $autoremove->remove($campaignName);

}
