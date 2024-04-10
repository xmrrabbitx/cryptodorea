<?php


namespace Cryptodorea\Woocryptodorea\controllers\web3;

use Cryptodorea\Woocryptodorea\abstracts\web3\smartContractAbstract;
use Web3\Contract;
use Web3\Web3;
use Web3\Providers\HttpProvider;


/**
 * an interface for smart contract web3 php
 */
class smartContractController extends smartContractAbstract
{

    /**
     * @param $amount
     * @param $campaignName
     * @return void
     */
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


    public function compile():array
    {

        $jsonData = array(
            "language" => "Solidity",
            "sources" => array(
                "test.sol" => array(
                    "content" => "contract C { function f() public { } }"
                )
            ),
            "settings" => array(
                "outputSelection" => array(
                    "*" => array(
                        "*" => array("*")
                    )
                )
            )
        );

        $jsonData = json_encode($jsonData);

        // URL to send the POST request to
        $url = "https://cryptodorea.io/api/smartContract/compile";

        // Set content type header
        $header = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ];

        // Set stream context options
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", $header),
                'content' => $jsonData
            ]
        ];

        // Create stream context
        $context = stream_context_create($options);

        // Send the request and get response
        $response = file_get_contents($url, false, $context);

        // Decode JSON response
        $responseData = json_decode($response, true);

        // Check for errors
        if ($responseData[0] !== 'success') {
            // log error on wordpress log system
        }
        return $responseData;
    }

    /**
     * deploy initial smart contract
     * @return void
     */
    public function deployContract($metamaskInfo, $compiledConract): void
    {

        $userAddress = $metamaskInfo->userAddress;

        $web3 = new Web3(new HttpProvider('http://localhost:8545'));
        $eth = $web3->eth;

        $contract = new Contract('http://localhost:8545', $compiledConract['abi']);

        $contract->bytecode('0x' . $compiledConract['bytecode'])->new([
            'from' => '0x6d50cEBED465fcf13A6E3dC48740307A8b72578b',//$metamaskInfo->userAddress,
            'gas' => '0x200b20'
        ], function ($err, $result){
            if ($err) {
                echo "\nerror is:" . $err . "\n";
            }
            if ($result) {
                echo "\nTransaction has made:) id: " . $result . "\n";
            }
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

