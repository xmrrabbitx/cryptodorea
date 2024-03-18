<?php

/**
 * a class for pay controller
 */

require(WP_PLUGIN_DIR . "/woo-cryptodorea/abstracts/payAbstract.php");

class pay extends payAbstract{

    public function __construct(){

    }

    /**
     * check Shopping Counts of every user
     */
    public function checkCount(){
        $campaignName = array_keys(get_option('campaigninfo_user'));
        foreach(array_keys(get_option('campaigninfo_user')) as $campaignName){
            $campaign = get_option('campaigninfo_user')[$campaignName];
            $limit = get_transient($campaignName)['shoppingCount'];
            if($campaign['count'] >= $limit){
                $this->checkExpire($campaignName);
            }
        }
    }

    /**
     * check Loyalty Program Expiration Date && Time
     */
    public function checkExpire($campaignName){

        $currentTime = currentDate();
        $currentDateMonth = unixToMonth($currentTime);
        $currentDateDay = unixToday($currentTime);

        $startCampaignDateMonth = get_transient($campaignName)['startDateMonth'];
        $startCampaignDateDay = get_transient($campaignName)['startDateDay'];
        $expCampaignDateMonth = get_transient($campaignName)['expDateMonth'];
        $expCampaignDateDay = get_transient($campaignName)['expDateDay'];

        $daysList = ['January'=>31, 'February'=>29, 'March'=>31, 'April'=>30, 'May'=>31, 'June'=>30, 'July'=>31, 'August'=>31, 'September'=>30, 'October'=>31, 'November'=>30, 'December'=>31];

        if($currentDateMonth === $startCampaignDateMonth || $currentDateMonth === $expCampaignDateMonth){
            if($currentDateDay >= $startCampaignDateDay || $currentDateDay <= $startCampaignDateDay){
               return $this->queuePay($campaignName);
            }
        }
        
    }

    /**
     * queue to pay
     */
    public function queuePay($campaignName){

        if(!get_option('dorea_queue_pay')){
            $campaignList = [$campaignName];
            add_option('dorea_queue_pay',$campaignList);
        }else{
            $campaignList = get_option('dorea_queue_pay');
            if(!in_array($campaignName, $campaignList)){
                array_push($campaignList, $campaignName);
                update_option('dorea_queue_pay',$campaignList);
            }
        }

    }

    /**
     * pay crypto to user
     */
    public function pay(){

        // GET Wallet address of user
        var_dump($_POST['dorea-modal-text']);

        // pay when queue trigger on specific date
        var_dump("pay controller function");
        return true;
    }

}