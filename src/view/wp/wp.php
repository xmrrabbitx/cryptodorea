<?php

/**
 * wp on each user request
 */

use Cryptodorea\Woocryptodorea\controllers\checkoutController;
use function Cryptodorea\Woocryptodorea\view\modals\claimCampaign\claimModal;

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

    }
}
