/**
 * load etherjs library
 * URL: https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js
 * Source Code: https://github.com/ethers-io/ethers.js
 */
import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "./etherv67.min.js";
import {abi} from "./doreaCompile.js";

jQuery(document).ready(async function($) {

    function convertWeiToEther(amount){

        const creditAmountBigInt = amount;
        const multiplier = 1e18;
        return creditAmountBigInt / multiplier;

    }

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

    let amounts = convertToWei(params.qualifiedUserEthers);

    let payFailBreak = localStorage.getItem('payFailBreak');
    let payStatus = localStorage.getItem('doreaFundStatus');
    let doreaRjectMetamask = document.getElementById('doreaRjectMetamask');

    if(payFailBreak){
        console.log(payStatus)
        if(payStatus) {
            if (payStatus === "open") {
                localStorage.removeItem('doreaPayStatus');
                $(doreaRjectMetamask).show('slow');
                await new Promise(r => setTimeout(r, 10000));
                $(doreaRjectMetamask).hide('slow');
                return false;
            } else if (payStatus === "confirm") {

                let contractAddress = params.contractAddress;
                let campaignName = JSON.parse(payFailBreak).campaignName;
                let _wpnonce = JSON.parse(payFailBreak)._wpnonce;
                let failedTime = JSON.parse(payFailBreak).failedTime;
                let _trxId = JSON.parse(payFailBreak).trxId;

                let time;
                if (Date.now() > (failedTime + 20000)) {
                    time = 0;
                } else {
                    time = ((failedTime + 20000) - Date.now());
                }

                const body = document.body;
                let failBreakReload = document.getElementById("doreaFailedBreakStatusLoading");
                $(failBreakReload).show();
                // Disable interactions
                body.style.pointerEvents = 'none';
                body.style.opacity = '0.5'; // Optional: Makes the body look grayed out
                body.style.userSelect = 'none'; // Disables text selection
                body.style.overflow = 'hidden'; // Prevent scrolling

                let timerStatus = localStorage.getItem("doreaTimer");
                let timer = time;
                let delayTime;
                // counter timer to syc previous transaction or pay it until expiration
                let doreaTimerLoading = document.getElementById("doreaTimerLoading");
                doreaTimerLoading.style.display = "block";
                if (timerStatus) {
                    for (timer; timer >= 0;) {
                        await new Promise(r => setTimeout(r, 1000));
                        doreaTimerLoading.innerHTML = parseInt(timer / 1000);
                        timer = timer - 1000;
                        delayTime = timer;
                    }
                }

                setTimeout(delay, delayTime)

                function delay() {
                    (async () => {

                        $(failBreakReload).hide();
                        // enable interactions
                        body.style.pointerEvents = 'visible';
                        body.style.opacity = '1';
                        body.style.userSelect = 'visible'; // enable text selection
                        body.style.overflow = 'visible'; // Prevent scrolling

                        const provider = new BrowserProvider(window.ethereum);

                        // Get the signer from the provider metamask
                        const signer = await provider.getSigner();

                        const contract = new ethers.Contract(contractAddress, abi, signer);

                        let balance = await contract.getBalance();
                        balance = convertWeiToEther(parseInt(balance));
                        let trxId = await contract.checkTrxIds(_trxId);

                        console.log(trxId)
                        if (trxId === true) {
                            jQuery.ajax({
                                type: "post",
                                url: params.ajax_url + '?_wpnonce=' + _wpnonce,
                                data: {
                                    action: "dorea_pay",
                                    data: JSON.stringify({
                                        "userList": params.usersList,
                                        "amountWei": amounts,
                                        'balance': balance,
                                        "campaignName": campaignName,
                                        "totalPurchases": params.totalPurchases,
                                        "claimedAmount": params.qualifiedUserEthers
                                    }),
                                },
                                complete: function (response) {
                                    // pop up message to reload the  page after interrupt transaction
                                    let failBreakModal = document.getElementById("failBreakModal");
                                    $(failBreakModal).show("slow");
                                    localStorage.removeItem('payFailBreak');
                                    localStorage.removeItem('doreaPayStatus');

                                    return false;
                                },
                            });

                        }
                        localStorage.removeItem('payFailBreak');
                        localStorage.removeItem('doreaPayStatus');

                    })();
                }
            }

        }
    }

    let failBreakReload = document.getElementById("failBreakReload");
    failBreakReload.addEventListener("click", async () => {
        window.location.reload();
    });

});