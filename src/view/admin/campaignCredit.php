<?php

/**
 * Crypto Cashback Campaign Credit
 */

use Cryptodorea\Woocryptodorea\controllers\web3\smartContractController;


function dorea_cashback_campaign_credit()
{

    print("campaign credit page");
    //var_dump(get_transient('dorea 7'));

    if(isset($_GET['campaignName'])) {

        $campaignName = $_GET['campaignName'];
        print("CHARGE CAMPAIN NAME" .  $campaignName);

        print("
            <form method='POST' action='" . esc_url(admin_url('admin-post.php')) . "' id='campaign_credit'>
                <input type='hidden' name='action' value='campaign_credit_charge'>
                <input type='hidden' name='campaignName' value= '".$campaignName."' >
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

                    const address = {
                        userAddress: userAddress,
                        userBalance: userBalance
                    };
     
                    // Perform action and send transaction data to PHP backend
                    const response = await fetch('admin.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json;UTF=8' },
                        body: JSON.stringify(address)
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

    $data = json_decode(file_get_contents('php://input'), true);

    if(isset($data)){
        $doreaWeb3 = new smartContractController();
        $doreaWeb3->deploy();
    }


}