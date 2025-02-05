<?php

/**
 * License: GNU General Public License v2 or later
 *
 * Plugin Name: Crypto Dorea: Crypto Cashback for WooCommerce
 * Description: A New Innovative Crypto CashBack for the most loyal customers
 * Version: 1.0.0
 * Author: Crypto Dorea Team
 * Author URI: https://cryptodorea.io
 */

define( 'DOREA_PLUGIN_FILE', __FILE__ );
define( 'DOREA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DOREA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

include_once __DIR__ . '/vendor/autoload.php';

include_once __DIR__ . '/src/loader.php';

