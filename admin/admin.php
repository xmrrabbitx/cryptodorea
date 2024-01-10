<?php

defined( 'ABSPATH' ) || exit;

include_once(WP_PLUGIN_DIR . '/dorea/config/conf.php');

/**
 * add a main page to menu in wordpress
 */
add_action('admin_menu', 'dorea_add_menu_page');

function dorea_add_menu_page() {

    $logo_path = plugin_dir_path(__FILE__) . 'icons/doreaLogo.svg';

    if (file_exists($logo_path)) {
        $logo_content = file_get_contents($logo_path);
        $base64_encoded = base64_encode($logo_content);

        /**
         * Dorea Cash Back Main Menu
         */
        add_menu_page(
            'Dorea Cash Back',   // Page title
            'Dorea Cash Back',        // Menu title
            'manage_options',     // Capability required to access
            'crypto-dorea-cashback',   // Menu slug (unique identifier)
            'dorea_main_page_content', // Callback function to display page content
            'data:image/svg+xml;base64,' . $base64_encoded, // Icon URL or dashicon class
            20 // Menu position (you can adjust this)
        );

        /**
         * Setting Menu
         */
        add_submenu_page(
            'crypto-dorea-cashback',
            'Setting Page',
            'settings',
            'manage_options',
            'settings',
            'dorea_main_setting_content'
        );
    
    }
    
}

/**
 *  main page content
 */ 
function dorea_main_page_content(){

    print("create cash back program");
    
    
}

/**
 * setting page content
 */
function dorea_main_setting_content(){

    print("Setting Page");
    print("</br>");
    print("initial config");
    print("

        </br>
        <form method='POST' action='http:///localhost/wp-admin/admin-post.php' id='init_config'>
            <input type='hidden' name='action' value='init_config'>
            <lable>name</lable>
            <input type='text' name='name'>
            </br>
            <lable>username</lable>
            <input type='text' name='username'>
            </br>
            <lable>recovery password</lable>
            <input type='text' name='recoveryPassword'>
            </br>

            <lable>what is your purpose?</lable>
            <input type='text' name='companyPurpose'>
            </br>

            <lable>how big your business is?</lable>
            <input type='text' name='companySize'>

            <button type='submit' onClick='setup_init_config()'>set up now!</button>
        </form>
        </br>
    ");

    // TODO: use encryption to encrypt data sent to api 
    // set initial config
    /*
    print("<script>
        function setup_init_config() {
            event.preventDefault();
            var form = document.getElementById('init_config');
            var formData = new FormData(form);

            let xhr = new XMLHttpRequest();
                xhr.open('POST', 'http://localhost:3000/api/auth/user', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            console.log(xhr.responseText);
                        }
                };
                                
            xhr.send(formData);
            
        }
    </script>");
    */
}

/**
 * set up init config
 */
add_action('admin_post_init_config', 'dorea_admin_init_config');
function dorea_admin_init_config(){
    static $init_config_name = 'init_config_setup';
    
    if(!empty($_POST['name'] && $_POST['username'] && $_POST['recoveryPassword'] && $_POST['companyPurpose'] && $_POST['companySize'])){

        $name = htmlspecialchars($_POST['name']);
        $username = htmlspecialchars($_POST['username']);
        $recoveryPassword = htmlspecialchars($_POST['recoveryPassword']);
        $purpose = htmlspecialchars($_POST['companyPurpose']);
        $companySize = htmlspecialchars($_POST['companySize']);

        $config = new config();
        if(!($config->check($init_config_name))){
            
            $init_config_values = [$name,$username,$recoveryPassword,$purpose,$companySize]; 
            
            $config->add($init_config_name, $init_config_values);
            
        }
        
    }
    
}