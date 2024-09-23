<?php

/**
 * loader class for dorea file
 */

// check security
defined( 'ABSPATH' ) || exit;

/*
// load initial libraries
print(' 
         <!-- load toastify library -->
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
');
*/

// load necessary files
include_once WP_PLUGIN_DIR . '/woo-cryptodorea/src/view/admin/admin.php';
include_once WP_PLUGIN_DIR . '/woo-cryptodorea/src/view/checkout/checkout.php';
include_once WP_PLUGIN_DIR . '/woo-cryptodorea/src/view/modals/claimCampaign.php';


/**
 * a Class for handling the Cash Back program
 */
class loader{

    public function __construct(){

        $this->removeCacheLogs();

        // set the session max lifetime to 2 hours
        $inactive = 7200;
        ini_set('session.gc_maxlifetime', $inactive);

        session_start();

        if (isset($_SESSION['campaignList_user']) && (time() - $_SESSION['time'] > $inactive)) {

            session_unset();
            session_destroy();
        }

    }

    /**
     * check and remove the size of log files in /debug directory
     */
    private function removeCacheLogs(){

        $logDirectory = WP_PLUGIN_DIR . "/woo-cryptodorea/debug/";
        $logFiles = glob($logDirectory . "*.log");
        $maxFileSize = 5 * 1024 * 1024;

        foreach ($logFiles as $logFile) {

            if(filesize($logFile) > $maxFileSize){

                unlink($logFile);

            }

        }

    }



}
