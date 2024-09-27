<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\campaignCreditAbstract;

/**
 * Controller to create_modify_delete cashback campaign
 */
class campaignCreditController extends campaignCreditAbstract
{

    public function encryptionGeneration($key, $value, $encryptionMessage)
    {
        // what if multiple campaigns existes?!!
        $currentEncryption = ["key" => bin2hex($key), "value"=> bin2hex($value), "encryptedMessage" => $encryptionMessage];
        return get_option('encryptionCampaign') ? update_option('encryptionCampaign', $currentEncryption) : add_option('encryptionCampaign', $currentEncryption);
    }

    public function nextEncryption($key, $value, $encryptionMessage)
    {
        $nextEncryption = ["key" => $key, "value"=> $value, "encryptedMessage" => $encryptionMessage];
        return get_option('nextEncryptionCampaign') ? update_option('nextEncryptionCampaign', $nextEncryption) : add_option('nextEncryptionCampaign', $nextEncryption);
    }

}
