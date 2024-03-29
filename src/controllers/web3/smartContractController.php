<?php

/**
 * an interface for smart contract web3 php
 */

namespace Cryptodorea\Woocryptodorea\controllers\web3;

use Cryptodorea\Woocryptodorea\abstracts\web3\smartContractAbstract;
use Web3\Web3;
use Web3\Providers\HttpProvider;



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

    /**
     * deploy initial smart contract
     * @return void
     */
    public function deployContract($contract)
    {
        $web3 = new Web3(new HttpProvider('http://localhost:8545'));
        $eth = $web3->eth;
        $eth->batch(true);
        $eth->protocolVersion();
        $eth->syncing();

        $eth->provider->execute(function ($err, $data) {
            if ($err !== null) {
                // do something
                return;
            }
            // do something
        });

        var_dump('deploy is done!!!');
    }

    /**
     * create initial smart contract
     * @return void
     */
    public function createContract(){

        // create initial smart contract
    }


}

