/**
 * load etherjs library
 * URL: https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js
 * Source Code: https://github.com/ethers-io/ethers.js
 */
import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "./etherv67.min.js";

import {abi} from "./doreaCompile.js";

let payCampaign = document.getElementById("dorea_pay");
let errorMessg = document.getElementById("dorea_error");
let successMessg = document.getElementById("dorea_success");

jQuery(document).ready(async function($) {

    payCampaign.addEventListener("click", async function () {

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

        function convertToWei(amounts) {

                let amountsBig = [];
                amounts.forEach(
                    (amount) => {
                        if ((typeof (amount) === "number") && (Number.isInteger(amount))) {

                            const creditAmountBigInt = BigInt(amount);
                            const multiplier = BigInt(1e18);
                            const result = creditAmountBigInt * multiplier;
                            amountsBig.push(parseInt(result));

                        } else {

                            const creditAmount = amount; // This is a floating-point number
                            const multiplier = BigInt(1e18); // This is a BigInt
                            const factor = 1e18;

                            // Convert the floating-point number to an integer
                            const creditAmountInt = BigInt(Math.round(creditAmount * factor));
                            const result = creditAmountInt * multiplier / BigInt(factor);
                            amountsBig.push(parseInt(result));
                        }
                    }
                )
                return amountsBig;
            }

            function convertWeiToEther(amount) {

                const creditAmountBigInt = amount;
                const multiplier = 1e18;
                return creditAmountBigInt / multiplier;

            }

            let payCampaignVal = payCampaign.id;
            const contractAddress = param.contractAddress;
            let campaignName = param.campaignName;

            await window.ethereum.request({method: "eth_requestAccounts"});
            const accounts = await ethereum.request({method: "eth_accounts"});
            const account = accounts[0];

            const provider = new BrowserProvider(window.ethereum);

            const signer = await provider.getSigner();

            let message = "you are siging message to fund the contract" + param.campaignName;

            const messageHash = ethers.id(message);

            const body = document.body;
            let doreaFailBreakLoading = document.getElementById("doreaFailedBreakStatusLoading");
            let doreaBeforeTrxModal = document.getElementById("doreaBeforeTrxModal");

            try {

                // show warning before Trx popup message
                $(doreaBeforeTrxModal).show("slow");
                await new Promise(r => setTimeout(r, 3000));
                $(doreaBeforeTrxModal).hide("slow");

                console.log("trigger!");

                // disable dorea fund button
                payCampaign.disabled = true;

                $(doreaFailBreakLoading).show();
                // Disable interactions
                body.style.pointerEvents = 'none';
                body.style.opacity = '0.5'; // Optional: Makes the body look grayed out
                body.style.userSelect = 'none'; // Disables text selection
                body.style.overflow = 'hidden'; // Prevent scrolling

                // sign hashed message
                const signature = await ethereum.request({
                    method: "personal_sign",
                    params: [messageHash, accounts[0]],
                });

                // split signature
                const r = signature.slice(0, 66);
                const s = "0x" + signature.slice(66, 130);
                const v = parseInt(signature.slice(130, 132), 16);

                let amounts = convertToWei(param.qualifiedUserEthers);

                let userAddresses = param.qualifiedWalletAddresses;

                let amountsSum = amounts.reduce((accumulator, currentValue) => {
                    return parseInt(accumulator) + parseInt(currentValue)
                }, 0);

                const contract = new ethers.Contract(contractAddress, abi, signer);

                let balance = await contract.getBalance();

                if (balance < amountsSum || parseInt(balance) === 0) {

                    errorMessg.innerHTML = "the campaign balance is not enough to pay!";
                    $(errorMessg).show("slow");
                    await new Promise(r => setTimeout(r, 2500));
                    $(errorMessg).hide("slow");
                    return true;
                }

                let trxId = param.trxId;
                let _wpnonce =  param.payAjaxNonce;
                let failedTime = Date.now();
                localStorage.setItem('payFailBreak', JSON.stringify({campaignName, trxId, _wpnonce, failedTime}) );

                localStorage.setItem("doreaTimer", true);
                localStorage.setItem("doreaPayStatus", 'open');

                let payObj = await contract.pay(
                    JSON.parse(userAddresses),
                    amounts,
                    param.trxId,
                    messageHash,
                    v,
                    r,
                    s
                )

                localStorage.setItem("doreaPayStatus", 'confirm');

                await payObj.wait().then(async (receipt) => {

                    // transaction on confirmed and mined
                    if (receipt) {

                            balance = await contract.getBalance();
                            balance = convertWeiToEther(parseInt(balance));

                            successMessg.innerHTML = "payment has been successfull!";
                            $(successMessg).show("slow");
                            await new Promise(r => setTimeout(r, 1500));
                            $(successMessg).hide("slow");

                            jQuery.ajax({
                                type: "post",
                                url: param.ajax_url + '?_wpnonce=' + param.payAjaxNonce,
                                data: {
                                    action: "dorea_pay",
                                    data: JSON.stringify({
                                        "userList":param.usersList,
                                        "amountWei": amounts,
                                        'balance': balance,
                                        "campaignName": campaignName,
                                        "totalPurchases": param.totalPurchases,
                                        "claimedAmount": param.qualifiedUserEthers,
                                        "trxId":param.trxId
                                    }),
                                },
                                complete: function (response) {

                                    $(doreaBeforeTrxModal).hide("slow");

                                    localStorage.removeItem('payFailBreak');
                                    localStorage.removeItem('doreaPayStatus');

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
                localStorage.removeItem('doreaPayStatus');


                // enable dorea fund button
                payCampaign.disabled = false;

                // enable interactions
                body.style.pointerEvents = 'visible';
                body.style.opacity = '1';
                body.style.userSelect = 'visible'; // enable text selection
                body.style.overflow = 'visible'; // Prevent scrolling

                if (typeof error.revert === "undefined") {
                    errorMessg.innerHTML  = "Something went wrong. please try again!";
                } else {
                    let err = error.revert.args[0];
                    if (err === "Insufficient balance") {
                        errorMessg.innerHTML = "Insufficient balance";
                    } else if (err === "User is not Authorized!!!") {
                        errorMessg.innerHTML = "You dont have permission to pay!";
                    } else {
                        errorMessg.innerHTML = "payment was not successfull! please try again!";
                    }
                }

                // show error popup message
                $(errorMessg).show("slow");
                await new Promise(r => setTimeout(r, 2500));
                $(errorMessg).hide("slow");
                return false;

            }

        // enable dorea fund button
        payCampaign.disabled = false;
    })

})