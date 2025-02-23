<?php

/**
 * loader class for dorea file
 */

// check security
defined( 'ABSPATH' ) || exit;

/**
 * load necessary admin files
 */

include_once DOREA_PLUGIN_DIR . '/src/view/admin/doreaMain.php';
include_once DOREA_PLUGIN_DIR . '/src/view/checkout/doreaCheckout.php';
include_once DOREA_PLUGIN_DIR . '/src/view/doreaMenu/doreaMenu.php';
