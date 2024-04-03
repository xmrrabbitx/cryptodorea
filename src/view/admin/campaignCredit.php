<?php

/**
 * Crypto Cashback Campaign Credit
 */

use Cryptodorea\Woocryptodorea\controllers\web3\smartContractController;
use Web3\Contract;
use Web3\Eth;
use Web3\Providers\HttpProvider;
use Web3\Web3;
use Web3\Utils;


add_action('admin_menu','compile');
function compile()
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

    // Check for errors
    if ($response === false) {
        echo 'Error: Unable to fetch response';
    } else {
        // Decode JSON response
        $responseData = json_decode($response, true);
        // Output the response
        var_dump($responseData);
    }
}

function dorea_cashback_campaign_credit()
{


    print("campaign credit page");
    //var_dump(get_transient('dorea 7'));

    if (isset($_GET['campaignName'])) {

        $campaignName = $_GET['campaignName'];
        print("CHARGE CAMPAIN NAME" . $campaignName);

        print("
            <form method='POST' action='" . esc_url(admin_url('admin-post.php')) . "' id='campaign_credit'>
                <input type='hidden' name='action' value='campaign_credit_charge'>
                <input type='hidden' name='campaignName' value= '" . $campaignName . "' >
                <input type='text' name='amount'>
                <button type='submit'>charge</button>
            </form>
        ");
    }

    print("
        
        <button id='charge'>Perform Action</button>
        
        <script>
        document.getElementById('charge').addEventListener('click', async () => {
            if (window.ethereum) {
                try {
                    
                    // Request access to Metamask
                    const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
                    const userAddress = accounts[0];

                    const userBalance = await window.ethereum.request({ method: 'eth_getBalance', params: [userAddress,'latest'] });

                    const metamaskInfo = {
                        userAddress: userAddress,
                        userBalance: userBalance
                    };

                    // Perform action and send transaction data to PHP backend
                    const response = await fetch('admin.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json;UTF=8' },
                        body: JSON.stringify(metamaskInfo)
                    });
                    
                    // Handle response from backend
                    const responseData = await response.json();
  
                } catch (error) {
                    console.error(error);
                }
            } else {
                console.error('Metamask not detected');
            }
        });
        </script>
    
    ");
}

/**
 * Campaign Credit
 */
add_action('admin_post_campaign_credit_charge', 'dorea_admin_campaign_credit_charge');

function dorea_admin_campaign_credit_charge()
{

    if(isset($_POST['campaignName'])) {

        $campaignName = $_POST['campaignName'];

        $doreaWeb3 = new smartContractController();
        $doreaWeb3->getAmount($_POST['amount'], $campaignName);
    }
}


add_action('admin_menu', 'dorea_admin_campaign_smart_contract');
function dorea_admin_campaign_smart_contract()
{

    //$web3 = new Web3(new HttpProvider('http://localhost:8545'));

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
    $eth = new Eth('http://localhost:8545');
    $eth->batch(true);
    $eth->protocolVersion();
    $eth->syncing();
    //$eth->compileSolidity();

    $eth->provider->execute(function ($err, $data) {
        if ($err !== null) {
            // do something
            return;
        }
    });


    //$data = json_decode(file_get_contents('php://input'), true);

    if (isset($data)) {
        // $doreaWeb3 = new smartContractController();
        //$doreaWeb3->deployContract(true);
    }

}
