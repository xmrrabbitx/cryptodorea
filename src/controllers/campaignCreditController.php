<?php

namespace Cryptodorea\Woocryptodorea\controllers;

use Cryptodorea\Woocryptodorea\abstracts\campaignCreditAbstract;

/**
 * Controller to create_modify_delete cashback campaign
 */
class campaignCreditController extends campaignCreditAbstract
{
    public function encryptionGeneration($campaignName, $key, $value, $encryptionMessage)
    {
        $currentEncryption = [ $campaignName => ["key" => bin2hex($key), "value"=> bin2hex($value), "encryptedMessage" => $encryptionMessage]];
        return get_option('encryptionCampaign') ? update_option('encryptionCampaign', array_merge(get_option('encryptionCampaign'),$currentEncryption)) : add_option('encryptionCampaign', $currentEncryption);
    }
}
