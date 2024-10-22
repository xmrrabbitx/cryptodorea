
import {
    ethers,
    BrowserProvider
} from "./ethers.min.js";

import {abi} from "./compile.js";

let claimContainer = document.getElementById("doreaClaimModal");
let claimError = document.getElementById("doreaClaimError");
let claimCampaignModal = document.querySelectorAll(".doreaClaim");
let claimCampaignContent = document.querySelectorAll(".doreaModalContent");
let closeCampaignModal = document.getElementById("doreaCloseModal");
let closeCampaignError = document.getElementById("doreaCloseError");
let errorMessg = document.getElementById("doreaClaimError");
let successMessg = document.getElementById("doreaClaimSuccess");

var dorea_cashbback_menu = document.querySelector('a[href*="dorea_cashbback_menu"]') ?? null;

jQuery(document).ready(async function($) {



    if(dorea_cashbback_menu) {
        // show modal on sidebar menu trigger
        dorea_cashbback_menu.addEventListener('click', function (event) {
            event.preventDefault();

            if(claimContainer) {
                $(claimContainer).show(2500);
            }else {

                $(claimError).show(2500);

            }
        });
    }

    claimCampaignContent.forEach(

        (element) =>
            claimContainer.appendChild(element)
    )

    let currentDate = new Date();
    let timerCheck = sessionStorage.getItem('doreaTimer');

    if(timerCheck < currentDate || timerCheck === null) {
        // show modal on timer
        await new Promise(r => setTimeout(r, 2500));
        $(claimContainer).show(3000);
    }

    claimCampaignModal.forEach(

        (element) =>
            element.addEventListener("click", async function(){

                let contractAddress = element.value.split("_")[0] ?? null;
                let walletAddress = element.value.split("_")[1] ?? null;
                let amount =  element.value.split("_")[2] ?? null;
                let _encValue = element.value.split("_")[3] ?? null;
                let _encMessage = element.value.split("_")[4] ?? null;
                let campaignName = element.value.split("_")[5] + "_" + element.value.split("_")[6] ?? null;
                let amountEther = element.value.split("_")[7] ?? null;
                let totalPurchases = element.value.split("_")[8] ?? null;

                function convertToWei(amount){

                    if( (typeof(amount) === "number") && (Number.isInteger(amount))){

                        const creditAmountBigInt = BigInt(amount);
                        const multiplier = BigInt(1e18);
                        return creditAmountBigInt * multiplier;

                    }
                    else{

                        const creditAmount = amount; // This is a floating-point number
                        const multiplier = BigInt(1e18); // This is a BigInt
                        const factor = 1e18;

                        // Convert the floating-point number to an integer
                        const creditAmountInt  = BigInt(Math.round(creditAmount * factor));
                        return creditAmountInt * multiplier / BigInt(factor);

                    }
                }
                function convertWeiToEther(amount){

                    const creditAmountBigInt = amount;
                    const multiplier = 1e18;
                    return creditAmountBigInt / multiplier;

                }

                await window.ethereum.request({ method: "eth_requestAccounts" });
                const accounts = await ethereum.request({ method: "eth_accounts" });

                if (window.ethereum) {

                    const userAddress = accounts[0];

                    const userBalance = await window.ethereum.request({
                        method: "eth_getBalance",
                        params: [userAddress, "latest"]
                    });

                    // check balance of metamask wallet
                    if(parseInt(userBalance) < 300000000000000){

                        let err = "not enough balance to support fee! \n please fund your wallet at least 0.0003 ETH!";
                        $(errorMessg).show("slow");
                        return false;
                    }

                    const provider = new BrowserProvider(window.ethereum);

                    const signer = await provider.getSigner();

                    let message = "Dorea Cashback: you are claiming your cashback now!";

                    const messageHash = ethers.id(message);

                    // sign hashed message
                    const signature = await ethereum.request({
                        method: "personal_sign",
                        params: [messageHash, accounts[0]],
                    });

                    // split signature
                    const r = signature.slice(0, 66);
                    const s = "0x" + signature.slice(66, 130);
                    const v = parseInt(signature.slice(130, 132), 16);

                    //console.log(messageHash)
                    //console.log(v)
                    //console.log(r)
                    //console.log(s)

                    if(amount !== null){

                        try{

                            const contract = new ethers.Contract(contractAddress, abi, signer);

                            let balance = await contract.getBalance();

                            if(balance < parseInt(amount)){

                                errorMessg.innerHTML = "the campaign reached to the end!";
                                $(errorMessg).show("slow");
                                await new Promise(r => setTimeout(r, 2500));
                                $(errorMessg).hide("slow");

                            }

                            await contract.pay(
                                walletAddress,
                                parseInt(amount),
                                _encValue.toString(),
                                _encMessage.toString(),
                                messageHash,
                                v,
                                r,
                                s
                            ).then(async function(response){
                                response.wait().then(async (receipt) => {
                                    // transaction on confirmed and mined
                                    if (receipt) {

                                        let balance = await contract.getBalance();
                                        balance = convertWeiToEther(parseInt(balance));

                                        successMessg.innerHTML = "payment has been successfull!";
                                        $(successMessg).show("slow");
                                        await new Promise(r => setTimeout(r, 1500));
                                        $(successMessg).hide("slow");


                                        let xhr = new XMLHttpRequest();

                                        // remove wordpress prefix on production
                                        xhr.open("POST", "#", true);
                                        xhr.onreadystatechange = async function() {
                                            if (xhr.readyState === 4 && xhr.status === 200) {

                                                // window.location.reload();
                                                $(claimContainer).hide("slow");
                                            }
                                        }

                                        xhr.send(JSON.stringify({"claimCampaign":{"amountWei":amount, 'balance':balance,'_encValue':_encValue, '_encMessage':_encMessage,"campaignName":campaignName,"totalPurchases":totalPurchases,"claimedAmount":amountEther}}));
                                    }
                                });
                            });

                        }catch (error) {
                            let err = error.revert.args[0];
                            if(err === "Transfer failed!"){
                                errorMessg.innerHTML = "Transaction was not successful!";
                            }
                            if(err === "Transaction expired!"){
                                errorMessg.innerHTML = "Transaction is not valid!";
                            }
                            if(err === "User sign is not Authorized!"){
                                errorMessg.innerHTML = "change your wallet address to claim!";
                            }
                            if(err === "User is not Authorized!"){
                                errorMessg.innerHTML = "you don't have permission to claim!";
                            }

                            $(errorMessg).show("slow");
                            await new Promise(r => setTimeout(r, 1000));
                            $(errorMessg).hide("slow");

                        }
                    }

                }
            })
    )

    if(closeCampaignModal) {
        closeCampaignModal.addEventListener("click", async function () {
            await new Promise(r => setTimeout(r, 100));
            $(claimContainer).hide("slow");
            return timer();
        });
    }

    function timer(){

        let timerCheck = sessionStorage.getItem('doreaTimer');
        let timerState = sessionStorage.getItem('doreaTimerState');
        let currentDate = new Date();
        let nextDate;
        let state;

        if (timerCheck === null || timerState === null || timerState === "0") {
                nextDate = currentDate.getTime() + 15 * 60000;
                state = "1";
        } else if (timerState === "1") {
                nextDate = currentDate.setDate(currentDate.getDate() + 1);
                state = "2";
        } else {
                nextDate = "";
                state = "0";
        }

        sessionStorage.setItem('doreaTimer', nextDate.toString());
        sessionStorage.setItem('doreaTimerState', state.toString());

    }

    if(closeCampaignError) {
        closeCampaignError.addEventListener("click", async function () {
            await new Promise(r => setTimeout(r, 100));
            $(claimError).hide("slow");
        });
    }
});
