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

    print('
        
        <script>
        let xhr = new XMLHttpRequest();

        xhr.open("GET", "http://127.0.0.1/wordpress/wp-admin/admin-post.php?action=loyalty_json_file", true);
xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            console.log(response[1]);
    }
};

xhr.send();
</script>
        
        
        <input id="creditAmount" type="text">
        <button id="metamask">Fund your Campaign</button>

        <script type="module">
        
         import {ethers, BrowserProvider, ContractFactory, parseEther, Wallet} from "https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js";

         // Request access to Metamask
         setTimeout(delay, 1000)
         function delay(){
             (async () => {

                  if(window.ethereum._state.accounts.length > 0){
                     
                      document.getElementById("metamask").style.display = "none";
                       
                  }else {
                      document.getElementById("metamask").style.display = "block";
                  }
                  
                    document.getElementById("metamask").addEventListener("click", async () => {
                             
                            let contractAmount = document.getElementById("creditAmount").value;
                            
                            
                            /*
                            if (window.ethereum) {
                                try {
                                  
                                    const accounts = await window.ethereum.request({ method: "eth_requestAccounts" });
                                    const userAddress = accounts[0];
                       
                                    const userBalance = await window.ethereum.request({ method: "eth_getBalance", params: [userAddress,"latest"] });

                                    const contractInfo = {
                                        userAddress: userAddress,
                                        userBalance: userBalance,
                                        contractAmount: contractAmount
                                    };
                                    
                    // JSON object of contract transaction (provided in your question)
                    const transaction = {
                        from: "0xD259dcA1DaD40c3bD798E308cF6c82DB43d8d1Dc",
                        gas: "0x2dc6c0",
                        value: "0x0",
                        data: "0x" + "608060405261061a806100115f395ff3fe608060405260043610610033575f3560e01c80636d4ce63c14610037578063c39328f114610061578063cc80f6f31461007d575b5f80fd5b348015610042575f80fd5b5061004b6100a7565b60405161005891906101f5565b60405180910390f35b61007b600480360381019061007691906103f3565b6100ae565b005b348015610088575f80fd5b506100916101a0565b60405161009e91906104c7565b60405180910390f35b5f47905090565b478111156100f1576040517f08c379a00000000000000000000000000000000000000000000000000000000081526004016100e890610531565b60405180910390fd5b5f5b825181101561019b575f8382815181106101105761010f61054f565b5b602002602001015173ffffffffffffffffffffffffffffffffffffffff166108fc8490811502906040515f60405180830381858888f1935050505090508061018d576040517f08c379a0000000000000000000000000000000000000000000000000000000008152600401610184906105c6565b60405180910390fd5b5080806001019150506100f3565b505050565b60606040518060400160405280601081526020017f74657374206d7272616262697421212100000000000000000000000000000000815250905090565b5f819050919050565b6101ef816101dd565b82525050565b5f6020820190506102085f8301846101e6565b92915050565b5f604051905090565b5f80fd5b5f80fd5b5f80fd5b5f601f19601f8301169050919050565b7f4e487b71000000000000000000000000000000000000000000000000000000005f52604160045260245ffd5b61026982610223565b810181811067ffffffffffffffff8211171561028857610287610233565b5b80604052505050565b5f61029a61020e565b90506102a68282610260565b919050565b5f67ffffffffffffffff8211156102c5576102c4610233565b5b602082029050602081019050919050565b5f80fd5b5f73ffffffffffffffffffffffffffffffffffffffff82169050919050565b5f610303826102da565b9050919050565b610313816102f9565b811461031d575f80fd5b50565b5f8135905061032e8161030a565b92915050565b5f610346610341846102ab565b610291565b90508083825260208201905060208402830185811115610369576103686102d6565b5b835b81811015610392578061037e8882610320565b84526020840193505060208101905061036b565b5050509392505050565b5f82601f8301126103b0576103af61021f565b5b81356103c0848260208601610334565b91505092915050565b6103d2816101dd565b81146103dc575f80fd5b50565b5f813590506103ed816103c9565b92915050565b5f806040838503121561040957610408610217565b5b5f83013567ffffffffffffffff8111156104265761042561021b565b5b6104328582860161039c565b9250506020610443858286016103df565b9150509250929050565b5f81519050919050565b5f82825260208201905092915050565b5f5b83811015610484578082015181840152602081019050610469565b5f8484015250505050565b5f6104998261044d565b6104a38185610457565b93506104b3818560208601610467565b6104bc81610223565b840191505092915050565b5f6020820190508181035f8301526104df818461048f565b905092915050565b7f496e73756666696369656e742062616c616e63650000000000000000000000005f82015250565b5f61051b601483610457565b9150610526826104e7565b602082019050919050565b5f6020820190508181035f8301526105488161050f565b9050919050565b7f4e487b71000000000000000000000000000000000000000000000000000000005f52603260045260245ffd5b7f5472616e73666572206661696c656400000000000000000000000000000000005f82015250565b5f6105b0600f83610457565b91506105bb8261057c565b602082019050919050565b5f6020820190508181035f8301526105dd816105a4565b905091905056fea2646970667358221220374f3d1847335ad79cfa5a694cec2212298fdeae81e9847d48a8a0201c7aead664736f6c63430008160033"
                    };

                    // Send the transaction for signing
                    const signedTransaction = await window.ethereum.request({
                        method: "eth_sendTransaction",
                        params: [transaction]
                    });



                                    let xhr = new XMLHttpRequest();
                                    xhr.open("POST", "#", true);
                                    xhr.setRequestHeader("Accept", "application/json");
                                    xhr.setRequestHeader("Content-Type", "application/json");
                                    xhr.onreadystatechange = function() {
                                        if (xhr.readyState === 4 && xhr.status === 200) {
                                        
                                            //console.log("add to cash back session is set");
                                           //console.log(xhr.responseText);
                                        }
                                    };
                                        
                                    xhr.send(JSON.stringify(contractInfo));

                                    // hide charge button after metamask window closed
                                    ///document.getElementById("metamask").style.display = "none";
                                   
                                }catch (error) {
                                    console.error(error);
                                }
                            } else {
                                console.error("Metamask not detected");
                            }
                            */
                            
                            
                              if (window.ethereum) {


                                    const accounts = await window.ethereum.request({method: "eth_requestAccounts"});
                                    const userAddress = accounts[0];
                        
                                    const userBalance = await window.ethereum.request({
                                        method: "eth_getBalance",
                                        params: [userAddress, "latest"]
                                    });
                        
                                    //const provider = new ethers.JsonRpcProvider("https://polygon-amoy.g.alchemy.com/v2/LuZ5CnAEURDtdQRwm9VJlkHRQR29Kw_a");
                                    //const provider = new ethers.JsonRpcProvider("http://127.0.0.1:8545");
                                    const provider = new BrowserProvider(window.ethereum);
                        
                                    // Get the signer from the provider metamask
                                    const signer = await provider.getSigner();
                        
                                   // const privateKey = "0x37483b8eebc0281371d439a846b6114f2e6cda020d92453b89285306a099ff88"; // Replace with the private key of the account from Ganache
                                   // const signer = new ethers.Wallet(privateKey, provider);
                       
                                    const factory = new ContractFactory("", "",signer);
                        
                                    // If your contract requires constructor args, you can specify them here
                                    //const contract = await factory.deploy();
                        
                                    const contract = new ethers.Contract("0x98999E3FaC5dd0d4444A727e076B4f21c45F066f", compiledContract.abi, signer);
                        
                                    const tx = await contract.show();
                                    console.log(tx)
                            }


});
          
              })();
          }
          



        </script>
    
    ');



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
/*
    $contractInfo = json_decode(file_get_contents('php://input', true));

    if (isset($contractInfo)) {
         $doreaWeb3 = new smartContractController();
         $compiledConract = $doreaWeb3->compile();

         if($compiledConract){
             $doreaWeb3->deployContract($contractInfo, $compiledConract);
         }

    }
*/
}





/**
 * Campaign Credit
 */
add_action('admin_post_loyalty_json_file', 'dorea_admin_loyalty_json_file');

function dorea_admin_loyalty_json_file()
{

    $loyaltyJson = file_get_contents(WP_PLUGIN_DIR . '/woo-cryptodorea/loyalty.json');
    $compiledContract = json_decode($loyaltyJson);

    $abi = $compiledContract->abi;
    $bytecode = $compiledContract->bytecode->object;
    //var_dump($abi);
    $responseArray = [$abi, $bytecode];
    header('Content-Type: application/json');

    // Echo the JSON-encoded response
    echo json_encode($responseArray);
    exit;
   // return json_encode([$abi,$bytecode]);

}