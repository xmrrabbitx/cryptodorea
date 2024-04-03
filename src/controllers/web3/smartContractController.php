<?php

/**
 * an interface for smart contract web3 php
 */

namespace Cryptodorea\Woocryptodorea\controllers\web3;

use Cryptodorea\Woocryptodorea\abstracts\web3\smartContractAbstract;
use Web3\Contract;
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
        $abi = null;
        $contract = new Contract('http://localhost:8545', $abi);
        $contractCode = <<<EOF
pragma solidity ^0.8.0;

contract SimpleStorage {
    uint storedData;

    function set(uint x) public {
        storedData = x;
    }

    function get() public view returns (uint) {
        return storedData;
    }
}
EOF;

// Compile the Solidity contract
        $compiled = $web3->eth->compileSolidity($contractCode);

// Retrieve the ABI
        $abi = $compiled['contracts']['SimpleStorage']['abi'];

// Output the ABI
        var_dump($abi);


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

