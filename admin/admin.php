<?php

defined( 'ABSPATH' ) || exit;

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

function dorea_main_page_content(){

    print("create cash back program");
    
    
}


function dorea_main_setting_content(){

    print("Setting Page");
    print("</br>");
    print("initial config");
    print("

        </br>
        <form method='POST' action='#' id='init_config'>
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
            <input type='text' name='purpose'>
            </br>

            <lable>how big your business is?</lable>
            <input type='text' name='size'>

            <button onClick='setup_init_config()'>set up now!</submit>
        </form>
        </br>
    ");

      // set initial config
      print("<script>
        function setup_init_config() {
            event.preventDefault();
            var form = document.getElementById('init_config');
            var formData = new FormData(form);

            let xhr = new XMLHttpRequest();
                xhr.open('POST', 'https://cryptodorea.io/api/get', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            console.log(xhr.responseText);
                        }
                };
                                
            xhr.send(formData);
            
        }
    </script>");
    
}