/**
 * load etherjs library
 * URL: https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js
 * Source Code: https://github.com/ethers-io/ethers.js
 */
import {
    BrowserProvider,
    ContractFactory, ethers,
} from "./etherv67.min.js";

import {abi,bytecode} from "./doreaCompile.js";

const doreaBeforeTrxModal = document.getElementById("doreaBeforeTrxModal");
let successMessg = document.getElementById("dorea_success");

// Request access to Metamask
setTimeout(delay, 1000)
function delay(){
    (async () => {
        jQuery(document).ready(async function($) {

            document.getElementById("doreaFund").addEventListener("click", async () => {

                // connect to Arbitrum One  Mainnet
                await window.ethereum.request({
                    method: "wallet_addEthereumChain",
                    params: [{
                        chainId: "0xa4b1",
                        rpcUrls: ["https://arb1.arbitrum.io/rpc"],
                        chainName: "Arbitrum One",
                        nativeCurrency: {
                            name: "ARB",
                            symbol: "ETH",
                            decimals: 18,
                        },
                        blockExplorerUrls: ["https://arbitrum.blockscout.com/"]
                    }]
                });

                let errorMessg = document.getElementById("errorMessg");
                const metamaskError = document.getElementById("dorea_metamask_error");

                // check Metamask extension is installed!
                if(window.ethereum){

                    function convertWeiToEther(amount){

                        const creditAmountBigInt = amount;
                        const multiplier = 1e18;
                        return creditAmountBigInt / multiplier;

                    }

                    let campaignName = param.campaignName;


                    let contractAmount = document.getElementById("creditAmount").value;

                    if (contractAmount === "") {
                        metamaskError.style.display = "block";
                        errorMessg.innerHTML = "cryptocurrency amount could not be left empty!";

                        $(errorMessg).show("slow");
                        await new Promise(r => setTimeout(r, 2500));
                        $(errorMessg).hide("slow");

                        document.getElementById("doreaFund").disabled = false;
                        return false;
                    }
                    else if ( (/[^.0-9 ]/g.exec(contractAmount)) )  {

                        metamaskError.style.display = "block";
                        errorMessg.innerHTML = "cryptocurrency amount must be in the decimal format!";

                        $(errorMessg).show("slow");
                        await new Promise(r => setTimeout(r, 2000));
                        $(errorMessg).hide("slow");

                        document.getElementById("doreaFund").disabled = false;
                        return false;

                    }
                    else {
                        metamaskError.style.display = "none";
                    }


                    const accounts = await window.ethereum.request({method: "eth_requestAccounts"});
                    const userAddress = accounts[0];

                    const userBalance = await window.ethereum.request({
                            method: "eth_getBalance",
                            params: [userAddress, "latest"]
                    });

                    // check balance of metamask wallet
                    if (parseInt(userBalance) < 300000000000000) {

                        document.getElementById("doreaFund").disabled = false;
                        errorMessg.innerHTML = "not enough balance to support fee! \n please fund your wallet at least 0.0003 ETH!";

                        $(errorMessg).show("slow");
                        await new Promise(r => setTimeout(r, 2500));
                        $(errorMessg).hide("slow");

                        return false;

                    }

                    const body = document.body;
                    let doreaFailBreakLoading = document.getElementById("doreaFailedBreakStatusLoading");

                    try {

                        // show warning before Trx popup message
                        $(doreaBeforeTrxModal).show("slow");
                        await new Promise(r => setTimeout(r, 3000));
                        $(doreaBeforeTrxModal).hide("slow");

                        $(doreaFailBreakLoading).show();
                        // Disable interactions
                        body.style.pointerEvents = 'none';
                        body.style.opacity = '0.5'; // Optional: Makes the body look grayed out
                        body.style.userSelect = 'none'; // Disables text selection
                        body.style.overflow = 'hidden'; // Prevent scrolling

                        document.getElementById("doreaFund").disabled = true;
                        const provider = new BrowserProvider(window.ethereum);

                        // Get the signer from the provider metamask
                        const signer = await provider.getSigner();

                        const factory = new ContractFactory(abi, bytecode, signer)

                        let contractAmountBigInt;

                        // calculate 10% of amount
                        contractAmount = parseFloat(contractAmount) / (1-0.1);

                        if ((typeof (contractAmount) === "number") && (Number.isInteger(contractAmount))) {
                            const creditAmountBigInt = BigInt(contractAmount);
                            const multiplier = BigInt(1e18);
                            contractAmountBigInt = creditAmountBigInt * multiplier;

                        } else {

                            const creditAmount = contractAmount; // This is a floating-point number
                            const multiplier = BigInt(1e18); // This is a BigInt
                            const factor = 1e18;

                            // Convert the floating-point number to an integer
                            const creditAmountInt = BigInt(Math.round(creditAmount * factor));
                            contractAmountBigInt = creditAmountInt * multiplier / BigInt(factor);
                        }

                        localStorage.setItem("doreaTimer", true);

                        let contractObj = await factory.deploy(
                            {
                                value: contractAmountBigInt.toString(),
                                gasLimit: 3000000,
                            }
                        );

                        let _wpnonce =  param.ajaxNonce;
                        let failedTime = Date.now();
                        let contractAddress = contractObj.target;
                        localStorage.setItem('deployFailBreak', JSON.stringify({contractAddress, campaignName, failedTime, _wpnonce}) );

                        contractObj.waitForDeployment().then(async (receipt) => {

                            successMessg.innerHTML = "payment has been successfull!";
                            $(successMessg).show("slow");
                            await new Promise(r => setTimeout(r, 1500));
                            $(successMessg).hide("slow");

                            let contractAddress = receipt.target;

                            if (receipt) {

                                const contract = new ethers.Contract(contractAddress, abi, signer);

                                let balance = await contract.getBalance();
                                balance = convertWeiToEther(parseInt(balance));

                                jQuery.ajax({
                                    type: "post",
                                    url: param.ajax_url + '?_wpnonce=' + param.ajaxNonce,
                                    data: {
                                        action: "dorea_contract_address",  // the action to fire in the server
                                        data: JSON.stringify({
                                            "contractAddress":contractAddress,
                                            "contractAmount": balance,
                                            "campaignName":campaignName
                                        }),
                                    },
                                    complete: function (response) {

                                        $(doreaBeforeTrxModal).hide("slow");

                                        localStorage.removeItem('deployFailBreak');

                                        window.location.reload();

                                        $(doreaFailBreakLoading).hide();
                                        // enable interactions
                                        body.style.pointerEvents = 'visible';
                                        body.style.opacity = '1';
                                        body.style.userSelect = 'visible'; // enable text selection
                                        body.style.overflow = 'visible'; // Prevent scrolling

                                        return false;
                                    },
                                });

                            }

                        });

                    }
                    catch (error) {

                        $(doreaBeforeTrxModal).hide("slow");
                        $(doreaFailBreakLoading).hide();

                        localStorage.removeItem('deployState');

                        document.getElementById("doreaFund").disabled = false;

                        // enable interactions
                        body.style.pointerEvents = 'visible';
                        body.style.opacity = '1';
                        body.style.userSelect = 'visible'; // enable text selection
                        body.style.overflow = 'visible'; // Prevent scrolling

                        errorMessg.innerHTML = "Funding the Contract was not successfull! please try again";

                        $(errorMessg).show("slow");
                        await new Promise(r => setTimeout(r, 1500));
                        $(errorMessg).hide("slow");

                        return false;

                    }

                    // enable dorea fund button
                    document.getElementById("doreaFund").disabled = false;

                }
                else{

                    $(doreaBeforeTrxModal).hide("slow");

                    metamaskError.style.display = "block";
                    errorMessg.innerHTML = "please install Metamask extension!";
                    $(errorMessg).show("slow");
                    await new Promise(r => setTimeout(r, 2500));
                    $(errorMessg).hide("slow");

                }
            })
        });
    })();
}
