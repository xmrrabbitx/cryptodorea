<?php

namespace Cryptodorea\Woocryptodorea\utilities;

use Cryptodorea\Woocryptodorea\abstracts\utilities\encryptAbstract;

/**
 * encrypt data
 */
class Encrypt extends encryptAbstract
{

    public function currentDate()
    {

    }

    public function encrypt($data)
    {

        // random bytes for random secret
        $randomSecret = base64_encode(random_bytes(64));

        $data['random_secret'] = $randomSecret;
        $secretHash = hash('sha256',json_encode($data));

        if(!get_option($data['campaignName'] . '_secretHash')){
            add_option('random_secret', $randomSecret);
            add_option($data['campaignName'] . '_secretHash', $secretHash);
        }else{
            update_option('random_secret', base64_encode(random_bytes(64)));
            update_option($data['campaignName'] . '_secretHash', $secretHash);
        }

        return $secretHash;

    }

}