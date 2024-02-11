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
                die("time to pay!");
            }

        }
    
    }

    /**
     * check Loyalty Program Expiration Date && Time
     */
    public function checkExpire(){

    }

}