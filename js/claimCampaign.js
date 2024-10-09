
import {
    ethers,
    BrowserProvider
} from "./ethers.min.js";

import {abi} from "./compile.js";

let claimContainer = document.getElementById("doreaClaimModal");
let claimCampaignModal = document.querySelectorAll(".doreaClaim");
let claimCampaignContent = document.querySelectorAll(".doreaModalContent");
let closeCmampaignModal = document.getElementById("doreaCloseModal");
let errorMessg = document.getElementById("doreaClaimError");
let successMessg = document.getElementById("doreaClaimSuccess");

jQuery(document).ready(async function($) {

    claimCampaignContent.forEach(

        (element) =>
            claimContainer.appendChild(element)
    )
    //await new Promise(r => setTimeout(r, 2500));
    $(claimContainer).show(0);
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

                    if(amount !== null){

                        try{

                            const contract = new ethers.Contract(contractAddress, abi, signer);

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

                                        successMessg.innerHTML = "payment has been successfull!";
                                        $(successMessg).show("slow");
                                        await new Promise(r => setTimeout(r, 1500));
                                        $(successMessg).hide("slow");

                                        //let balance = await contract.getBalance();
                                        //balance = convertWeiToEther(parseInt(balance));

                                        //$(claimContainer).hide("slow");

                                        let xhr = new XMLHttpRequest();

                                        // remove wordpress prefix on production
                                        xhr.open("POST", "#", true);
                                        xhr.onreadystatechange = async function() {
                                            if (xhr.readyState === 4 && xhr.status === 200) {

                                                // window.location.reload();
                                            }
                                        }
                                        xhr.send(JSON.stringify({"amount":amount,'_encValue':_encValue, '_encMessage':_encMessage,"campaignName":campaignName,"totalPurchases":totalPurchases,"claimedAmount":amountEther}));
                                    }
                                });
                            });

                        }catch (error) {
                            console.log(error)
                            errorMessg.innerHTML = error.revert.args[0];
                            $(errorMessg).show("slow");
                            await new Promise(r => setTimeout(r, 1000));
                            $(errorMessg).hide("slow");


                        }
                    }

                }
            })
    )

    closeCmampaignModal.addEventListener("click", async function (){
        await new Promise(r => setTimeout(r, 100));
        $(claimContainer).hide("slow");
    });
});
