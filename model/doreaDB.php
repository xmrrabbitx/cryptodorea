<?php

require(WP_PLUGIN_DIR . "/dorea/exceptions/databaseError.php");
require(WP_PLUGIN_DIR . "/dorea/abstracts/doreaDbAbstract.php");

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

            $this->removeCacheLogs();

        } catch (Exception $error) {
            databaseError('- Connection failed: ' . $error);
        }
    }

   /**
    * check and remove the size of log files in /debug directory
    */
   private function removeCacheLogs(){
        $logDirectory = WP_PLUGIN_DIR . "/dorea/debug/";
        $logFiles = glob($logDirectory . "*.log");
        $maxFileSize = 5 * 1024 * 1024;
    
        foreach ($logFiles as $logFile) {

            if(filesize($logFile) > $maxFileSize){

                unlink($logFile);

            }
        
        }
    }

    public function addtoCashBack($addToChProgram, $loyaltyName, $exp){

        if($addToChProgram && $addToChProgram === true){

            set_transient($loyaltyName, $addToChProgram, $exp);

        }
       

    }
  
}