<?php

/**
 * Crypto Cashback Campaign
 */

use Cryptodorea\Woocryptodorea\controllers\cashbackController;
use Cryptodorea\Woocryptodorea\utilities\dateCalculator;

function dorea_cashback_campaign_content(){

    // utilities helper functions
    $dateCalculator = new dateCalculator();
    $currentTime = $dateCalculator->currentDate();
    $currentDate = $dateCalculator->unixToMonth($currentTime);
    $currentDay = (int) $dateCalculator->unixToDay($currentTime);
    $futureDate = $dateCalculator->futureDate(7,1,2022);
    $futureDate = $dateCalculator->unixToMonth($futureDate);

    $monthsList = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $daysList = ['January'=>31, 'February'=>29, 'March'=>31, 'April'=>30, 'May'=>31, 'June'=>30, 'July'=>31, 'August'=>31, 'September'=>30, 'October'=>31, 'November'=>30, 'December'=>31];

    print("campaign page");
    print("
    
        </br>
        <form method='POST' action='".esc_url(admin_url('admin-post.php'))."' id='cashback_campaign'>
            
            <input type='hidden' name='action' value='cashback_campaign'>
           
            <lable>campaign name</lable>
            <input type='text' name='campaignName'>
            </br>

            <lable>crypto type</lable>
            <input type='text' name='cryptoType'>
            </br>

            <lable>amount</lable>
            <input type='text' name='cryptoAmount'>
            </br>

            <lable>Shopping Counts</lable>
            <input type='text' name='shoppingCount'>
            </br>
            
            <lable>start date</lable>
            <select name='startDateMonth' id='startDateMonth'>
            ");

            foreach($monthsList as $month){

                if($month === $currentDate){
                    print("<option value='".$month."' selected>".$month."</option>");
                }
                else{
                    print("<option value='".$month."'>".$month."</option>");
                }
            }

            print("</select>
                    <span>
                    <select name='startDateDay' id='startDateDay'>");

            for($days=1;$days <= $daysList[$currentDate]; $days++){
                if($days === $currentDay){
                    print("
                    <option value='".$days."' selected>".$days."</option>
                    ");
                }
                print("
                    <option value='".$days."'>".$days."</option>
                    ");
            }
            
            print("</select>
            </span>
            </br>

            <lable>expire date</lable>
            <select name='expDateMonth' id='expDateMonth'>
            ");
            $next = "";
            foreach($monthsList as $month){
                $nextMonth = next($monthsList);
                if($month === $currentDate){ 
                    print("<option value='".$month."'>".$month."</option>");
                    print("<option value='".$nextMonth ."' selected>".$nextMonth."</option>");
                    $next = $nextMonth;
                }elseif($month !== $next){
                    print("<option value='".$month."'>".$month."</option>");
                }
              
            }

            print("</select>
                <span><select name='expDateDay' id='expDateDay'>");

            $nextDay = 30 - ($daysList[$currentDate] - $currentDay);
            for($days=1; $days <= $daysList[$next]; $days++){
                if($days === $nextDay){
                    print("
                    <option value='".$days."' selected>".$days."</option>
                    ");
                }
                print("
                    <option value='".$days."'>".$days."</option>
                    ");
            }
            
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

    static $home_url = 'admin.php?page=crypto-dorea-cashback';

    if(!empty($_POST['campaignName'] && $_POST['cryptoType'] && $_POST['startDateMonth'] && $_POST['startDateDay'] && $_POST['expDateMonth'] && $_POST['expDateDay'])){
       
            $campaignName = htmlspecialchars($_POST['campaignName']);
            $cryptoType = htmlspecialchars($_POST['cryptoType']);
            $cryptoAmount = (int)htmlspecialchars($_POST['cryptoAmount']);
            $shoppingCount = (int)htmlspecialchars($_POST['shoppingCount']);
            $startDateMonth = htmlspecialchars($_POST['startDateMonth']);
            $startDateDay = htmlspecialchars($_POST['startDateDay']);
            $expDateMonth = htmlspecialchars($_POST['expDateMonth']);
            $expDateDay= htmlspecialchars($_POST['expDateDay']);


            $cashback = new cashbackController();
            if(strlen($campaignName) < 25 ){
               
                if(empty(get_option('campaign_list')) || get_option('campaign_list') === NULL){
                    
                    delete_option('campaign_list');
                }

                $cashback->create($campaignName, $cryptoType, $cryptoAmount, $shoppingCount, $startDateMonth, $startDateDay, $expDateMonth, $expDateDay);

                // head to the admin page of Dorea
                wp_redirect('admin.php?page=credit&cashbackName='.$campaignName);

            }else{
               die('exceed characters limit!');
            }
            
           
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