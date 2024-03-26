<?php

/**
 * Crypto Cashback Campaign Credit
 */

use Cryptodorea\Woocryptodorea\controllers\web3\smartContractController;


function dorea_cashback_campaign_credit()
{

    print("campaign credit page");

    //remove campaign name after test
    $campaignName = 'dorea';
    var_dump(get_transient($campaignName));

    if(empty(get_transient($campaignName)['contractBalance'])) {
        print("
            <form method='POST' action='" . esc_url(admin_url('admin-post.php')) . "' id='campaign_credit'>
                <input type='hidden' name='action' value='campaign_credit_charge'>
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
                    await window.ethereum.request({ method: 'eth_requestAccounts' });
                    
                    const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
                    const userAddress = accounts[0];
                    const address = {
                        userAddress: userAddress,
                    };
                    console.log(userAddress);
                    // Perform action and send transaction data to PHP backend
                  
                    const response = await fetch('admin.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(address)
                    });
                    
                    // Handle response from backend
                    const responseData = await response.json();
                    console.log(responseData);
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

    //$userAddress = json_decode(file_get_contents('php://input'), true);
    //var_dump($userAddress);

    //remove campaign name after test
    $campaignName = 'dorea';

    $doreaWeb3 = new smartContractController();
    $doreaWeb3->getAmount($_POST['amount'], $campaignName);

}