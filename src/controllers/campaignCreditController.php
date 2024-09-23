<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\campaignCreditAbstract;

/**
 * Controller to create_modify_delete cashback campaign
 */
class campaignCreditController extends campaignCreditAbstract
{

    public function encryptionGeneration($campaignName,$key, $value, $encryptionMessage)
    {
        // what if multiple campaigns existes?!!
        $currentEncryption = ["key"=>bin2hex($key), "value"=>bin2hex($value), "encryptedMessage"=>$encryptionMessage];
        return get_option($campaignName) ? update_option($campaignName, $currentEncryption) : add_option($campaignName, $currentEncryption);
    }

}
