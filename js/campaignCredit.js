
import {
    BrowserProvider,
    ContractFactory, ethers,
} from "./ethers.min.js";

import {abi,bytecode} from "./compile.js";

// Request access to Metamask
setTimeout(delay, 1000)
function delay(){
    (async () => {
        jQuery(document).ready(async function($) {
            document.getElementById("doreaFund").addEventListener("click", async () => {

                let errorMessg = document.getElementById("errorMessg");
                const metamaskError = document.getElementById("dorea_metamask_error");

                // check Metamask extension is installed!
                if(window.ethereum){

                    function convertWeiToEther(amount){

                        const creditAmountBigInt = amount;
                        const multiplier = 1e18;
                        return creditAmountBigInt / multiplier;

                    }

                    let campaignName = params.campaignName;


                    let contractAmount = document.getElementById("creditAmount").value;

                    if (contractAmount === "") {
                        metamaskError.style.display = "block";
                        errorMessg.innerHTML = "cryptocurrency amount could not be left empty!";

                        $(errorMessg).show("slow");
                        await new Promise(r => setTimeout(r, 2500));
                        $(errorMessg).hide("slow");

                        document.getElementById("doreaFund").disabled = false;
                        return false;
                    } else if (!Number.isInteger(parseInt(contractAmount))) {

                        metamaskError.style.display = "block";
                        errorMessg.innerHTML = "cryptocurrency amount must be in the decimal format!";

                        $(errorMessg).show("slow");
                        await new Promise(r => setTimeout(r, 1500));
                        $(errorMessg).hide("slow");

                        document.getElementById("doreaFund").disabled = false;
                        return false;

                    } else {
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

                    try {
                        let xhr = new XMLHttpRequest();

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

                        await factory.deploy(
                            {
                                value: contractAmountBigInt.toString(),
                                gasLimit: 3000000,
                            }
                        ).then(async function (response) {

                            let contractAddress = response.target;

                            // wait for deployment
                            response.waitForDeployment().then(async (receipt) => {

                                if (receipt) {

                                    const contract = new ethers.Contract(contractAddress, abi, signer);

                                    let balance = await contract.getBalance();
                                    balance = convertWeiToEther(parseInt(balance));

                                    jQuery.ajax({
                                        type: "post",
                                        url: `${window.location.origin}/wp-admin/admin-ajax.php`,
                                        data: {
                                            action: "dorea_contract_address",  // the action to fire in the server
                                            data: JSON.stringify({
                                                "contractAddress":contractAddress,
                                                "contractAmount": balance,
                                                "campaignName":campaignName
                                            }),
                                        },
                                        complete: function (response) {
                                            window.location.replace(`${window.location.origin}/wp-admin/admin.php?page=credit`);
                                        },
                                    });
                                }
                            });

                        });
                    } catch (error) {
                        document.getElementById("doreaFund").disabled = false;
                        errorMessg.innerHTML = "Funding the Contract was not successfull! please try again";

                        $(errorMessg).show("slow");
                        await new Promise(r => setTimeout(r, 1500));
                        $(errorMessg).hide("slow");

                        return false;

                    }

                    // enable dorea fund button
                    document.getElementById("doreaFund").disabled = false;

                }else{

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

