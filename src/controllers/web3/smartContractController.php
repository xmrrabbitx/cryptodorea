<?php

/**
 * an interface for smart contract web3 php
 */

namespace Cryptodorea\Woocryptodorea\controllers\web3;

use Cryptodorea\Woocryptodorea\abstracts\web3\smartContractAbstract;
use Web3\Web3;




class smartContractController extends smartContractAbstract
{

    public function getAmount($amount, $campaignName)
    {

        $campaignList  = get_option('campaign_list');
        if(in_array($campaignName, $campaignList)){
            $campaign = get_transient($campaignName);
            $campaign['contractBalance'] = $amount;
            set_transient($campaignName,$campaign);
        }
        wp_redirect('admin.php?page=credit');

    }

    public function deploy()
    {
        $web3 = new Web3('http://localhost:8545');
        var_dump($web3);
        var_dump('deploy is done!!!');

    }

}

