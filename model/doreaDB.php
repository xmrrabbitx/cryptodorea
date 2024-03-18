<?php

require(WP_PLUGIN_DIR . "/woo-cryptodorea/exceptions/databaseError.php");
require(WP_PLUGIN_DIR . "/woo-cryptodorea/abstracts/doreaDbAbstract.php");

/**
 * an interface to connect to a PDO_SQLite3 Database
 */
class doreaDB extends doreaDbAbstract {

    private $maxFileSize;
    private  $logFile;

   public function __construct() {

   }

   /**
    * check the status of Database connection
    */
   public function init(){
        global $wpdb;
        try {
            // Check if the wp_options table exists
            if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->options}'") != $wpdb->options) {
                databaseError('Cannot access wp_options table!');
            } 

        } catch (Exception $error) {
            databaseError('- Connection failed: ' . $error);
        }
    }
}