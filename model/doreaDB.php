<?php

require(WP_PLUGIN_DIR . "/dorea/exceptions/databaseError.php");
require(WP_PLUGIN_DIR . "/dorea/abstracts/abstractDoreaDB.php");

/**
 * an interface to connect to a PDO_SQLite3 Database
 */
class DoreaDB extends abstractDoreaDb {

    private $maxFileSize;
    private  $logFile;

   public function __construct() {

        global $wpdb;
        try {
            // Check if the wp_options table exists
            if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->options}'") != $wpdb->options) {
                databaseError('Cannot access wp_options table!');
            } 

            $this->removeCache();
            
        } catch (Exception $error) {
            databaseError('- Connection failed: ' . $error);
        }
   }

   private function removeCache(){

        $logDirectory = WP_PLUGIN_DIR . "/dorea/debug/";
        $logFiles = glob($logDirectory . "*.log");
        $maxFileSize = 5 * 1024 * 1024;
    
        foreach ($logFiles as $logFile) {

            if(filesize($logFile) > $maxFileSize){

                unlink($logFile);

            }
        
        }
   }
  
}