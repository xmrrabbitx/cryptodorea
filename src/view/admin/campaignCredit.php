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
             
            <script src='http://code.dappbench.com/browser-solc.min.js' type='text/javascript'></script>
            <script>
            console.log('test')
                
             console.log(BrowserSolc)
        </script>
        
        ");

    print("

        <button id='metamask'>Connect to MetaMask</button>

        
        <script>
         // Request access to Metamask
         setTimeout(delay, 1000)
         function delay(){
             (async () => {

                  if(window.ethereum._state.accounts.length > 0){
                     
                      document.getElementById('metamask').style.display = 'none';
                       
                  }else {
                      document.getElementById('metamask').style.display = 'block';
                  }
                  
              document.getElementById('metamask').addEventListener('click', async () => {
                           
                            if (window.ethereum) {
                                try {
                                  
                                    const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
                                    const userAddress = accounts[0];
                       
                                    //const userBalance = await window.ethereum.request({ method: 'eth_getBalance', params: [userAddress,'latest'] });

                                    // the issue is vpn
                                    const metamaskInfo = {
                                        userAddress: userAddress,
                                        userBalance: '0x0' //replace it with real userBalance
                                    };

                                    let xhr = new XMLHttpRequest();
                                    xhr.open('POST', '#', true);
                                    xhr.setRequestHeader('Accept', 'application/json');
                                    xhr.setRequestHeader('Content-Type', 'application/json');
                                    xhr.onreadystatechange = function() {
                                        if (xhr.readyState === 4 && xhr.status === 200) {
                                        
                                            //console.log('add to cash back session is set');
                                           //console.log(xhr.responseText);
                                        }
                                    };
                                        
                                    xhr.send(JSON.stringify(metamaskInfo));

                                    // hide charge button after metamask window closed
                                    ///document.getElementById('metamask').style.display = 'none';
                                   
                                }catch (error) {
                                    console.error(error);
                                }
                            } else {
                                console.error('Metamask not detected');
                            }
                        });
          
              })();
          }
          



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
    $metamaskInfo = json_decode(file_get_contents('php://input', true));

    if (isset($metamaskInfo)) {
         $doreaWeb3 = new smartContractController();
         $compiledConract = $doreaWeb3->compile();
         var_dump($compiledConract);
         if($compiledConract){
             $doreaWeb3->deployContract($metamaskInfo, $compiledConract);
         }

    }

}
