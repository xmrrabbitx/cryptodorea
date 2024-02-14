<?php

/**
 * a class for pay controller
 */

require(WP_PLUGIN_DIR . "/dorea/abstracts/payAbstract.php");

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

        $currentDate = Date();
        $campaignDate = get_option('campaigninfo_user')['date'];

        // check if date of campaign has not been expired!
        // we need trigger a modal view page to het wallet address
    }

    /**
     * queue to pay
     */
    public function queuePay(){
        
        // wp_schedule_event
    }

    /**
     * pay crypto to user
     */
    public function pay(){

        // pay when queue trigger on specific date
        die("time to pay");
    }

}