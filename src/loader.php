<?php

/**
 * loader class for dorea file
 */

// check security
defined( 'ABSPATH' ) || exit;

/**
 * load necessary admin files
 */
include_once DOREA_PLUGIN_DIR . '/src/view/admin/dorea.php';
include_once DOREA_PLUGIN_DIR . '/src/view/checkout/doreaCheckout.php';
include_once DOREA_PLUGIN_DIR . '/src/view/doreaMenu/doreaMenu.php';
include_once DOREA_PLUGIN_DIR . '/src/view/modals/userStatusCampaign.php';

// admin panel full loads
add_action('admin_menu','dorea_init');
function dorea_init():void
{
    /**
     * load necessary libraries files
     * tailwind css
     * ethersjs library
     */
    print('
    
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.13.5/ethers.min.js" integrity="sha512-A+iPLc1Ze9xA8XXa794jspu+TuEoJC/cIDNFXb+3Qpi69NRrHZg+IyrsRD+8m5Ui9030X6izkY4nLTG0tOasMw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    ');

}