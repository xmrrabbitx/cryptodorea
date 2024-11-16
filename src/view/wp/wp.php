<?php

/**
 * wp on each user request
 */

use Cryptodorea\DoreaCashback\controllers\checkoutController;
use function Cryptodorea\DoreaCashback\view\modals\userStatusCampaign\userStatusCampaign;

add_action('wp','wpRequest');
/**
 * @throws Exception
 */
function wpRequest()
{

    // check on Authentication
    if(is_user_logged_in()) {

        // autoremove deleted campaigns
        $checkout = new checkoutController();
        $checkout->autoRemove();

        userStatusCampaign();

    }

}
