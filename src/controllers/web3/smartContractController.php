<?php

/**
 * an interface for smart contract web3 php
 */

namespace Cryptodorea\Woocryptodorea\controllers\web3;

use Cryptodorea\Woocryptodorea\abstracts\web3\smartContractAbstract;



class smartContractController extends smartContractAbstract
{

    public function getAmount($amount, $campaignName)
    {

        $campaignList  = get_option('campaignlist_user');
        if(in_array($campaignName, $campaignList)){
            $campaign = get_transient($campaignName);
            $campaign['contractBalance'] = $amount;
            set_transient($campaignName,$campaign);
        }
        wp_redirect('admin.php?page=credit');

    }

    public function deploy($amount)
    {

        var_dump($amount);

    }

}

