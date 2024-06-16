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

        $secretHash = hash('sha256',json_encode($data));
        if(!get_option($data['campaignName'] . '_secretHash')){
            add_option($data['campaignName'] . '_secretHash', $secretHash);
        }else{
            update_option($data['campaignName'] . '_secretHash', $secretHash);
        }

        return $secretHash;

    }

}