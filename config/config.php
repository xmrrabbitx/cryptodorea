<?php

namespace cryptodorea\woocryptodorea\config;

require(WP_PLUGIN_DIR . "/woo-cryptodorea/abstracts/confAbstract.php");
use cryptodorea\woocryptodorea\abstracts\confAbstract;

/**
 * an interface for saving initial config
 */
class Config extends confAbstract{

    function __contruct(){

    }

    function a()
    {
        die("end!!!");
    }

    /**
     * check init config
     * @param string $key
     */
    public function check($key){

        if($key){
            return get_site_option($key);
        }

    }

    /**
     * add to init config
     * @param array $arr
     */
    public function add($key,$arr):bool{

        if($arr){
           return add_site_option($key,$arr);
        }
        return false;
    }

    /**
     * remove init config
     * @param string $key
     */
    public function remove($key):bool{

       if($key){
            return delete_site_option($key);
       }
       return false;
    }
    

}