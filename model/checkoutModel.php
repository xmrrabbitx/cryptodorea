<?php

require(WP_PLUGIN_DIR . "/dorea/abstracts/model/checkoutModelAbstract.php");

/**
 * an abstract for checkout model
 */
class checkoutModel extends checkoutModelAbstract{


    function __construct(){

    }

    public function list(){
       return get_option('campaignList_user') ?? null;
    }

    public function add($campaignNames){
        add_option('campaignList_user', $campaignNames);
    }

    public function update($campaignNames){
        update_option('campaignList_user', $campaignNames);
    }
}