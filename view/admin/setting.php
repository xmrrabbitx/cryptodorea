<?php

defined( 'ABSPATH' ) || exit;

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
    static $home_url = '/wp-admin/admin.php?page=crypto-dorea-cashback';
    
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
        
        header('Location: '.$home_url);

    }
    
}