
// load etherJs library
import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "./ethers.min.js";
import {abi} from "./compile.js";

let fundCampaign = document.getElementById("dorea_fund");
const errorMessg = document.getElementById("dorea_error");
const beforeTrxModal = document.getElementById("beforeTrxModal");
let successMessg = document.getElementById("dorea_success");

jQuery(document).ready(async function($) {

    if(sessionStorage.getItem('deployState')){
        location.replace(`${window.location.origin}/wp-admin/admin.php?page=crypto-dorea-cashback`);
    }

    fundCampaign.addEventListener("click", async function(){

        // connect to Arbitrium One  Mainnet
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

        /**
         *
         * @param amount
         * @returns {bigint}
         */
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

        /**
         *
         * @param amount
         * @returns {number}
         */
        function convertWeiToEther(amount){

                    const creditAmountBigInt = amount;
                    const multiplier = 1e18;
                    return creditAmountBigInt / multiplier;

        }

        const contractAddress = param.contractAddress;
        let campaignName = param.campaignName;

        await window.ethereum.request({method: "eth_requestAccounts"});
        let accounts = await ethereum.request({method: "eth_accounts"});
        let account = accounts[0];

        const provider = new BrowserProvider(window.ethereum);

        const signer = await provider.getSigner();

        let message = "you are siging message to fund the contract!";

        const messageHash = ethers.id(message);

        const body = document.body;

        try{

            // show warning before Trx popup message
            $(beforeTrxModal).show("slow");
            await new Promise(r => setTimeout(r, 2000));

            sessionStorage.setItem('deployState', 'false');

            // disable dorea fund button
            fundCampaign.disabled = true;

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

            let fundAgainAmount = convertToWei(param.remainingAmount);

            const contract = new ethers.Contract(contractAddress, abi, signer);

            await contract.fundAgain(
                messageHash,
                v,
                r,
                s,
                {
                    value: fundAgainAmount.toString(),
                    gasLimit :3000000,
                },
            ).then(async function(response){

                sessionStorage.setItem('fundFailBreak', JSON.stringify({campaignName}) );

                response.wait().then(async (receipt) => {

                    // transaction on confirmed and mined
                    if (receipt) {

                        successMessg.innerHTML = "payment has been successfull!";
                        $(successMessg).show("slow");
                        await new Promise(r => setTimeout(r, 1500));
                        $(successMessg).hide("slow");

                        let balance = await contract.getBalance();
                        balance = convertWeiToEther(parseInt(balance));

                        jQuery.ajax({
                            type: "post",
                            url: `${window.location.origin}/wp-admin/admin-ajax.php?_wpnonce=` + param.fundAjaxNonce,
                            data: {
                                action: "dorea_fund",
                                data: JSON.stringify({
                                    "balance": balance,
                                    "campaignName": campaignName,
                                }),
                            },
                            complete: function (response) {

                                $(beforeTrxModal).hide("slow");

                                sessionStorage.removeItem('fundFailBreak');
                                sessionStorage.removeItem('deployState');

                                window.location.reload();

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

            })
        }
        catch (error) {

            $(beforeTrxModal).hide("slow");

            sessionStorage.removeItem('deployState');

            // enable dorea fund button
            fundCampaign.disabled = false;

            // enable interactions
            body.style.pointerEvents = 'visible';
            body.style.opacity = '1';
            body.style.userSelect = 'visible'; // enable text selection
            body.style.overflow = 'visible'; // Prevent scrolling

            if(typeof error.revert === "undefined")   {
                errorMessg.innerHTML  = "Something went wrong. please try again!";
            } else {
                let err = error.revert.args[0];
                if(err === "Insufficient balance"){
                     errorMessg.innerHTML  = "Insufficient balance";
                }else if(err === "User is not Authorized!!!"){
                     errorMessg.innerHTML  = "You dont have permission to pay!";
                }else{
                     errorMessg.innerHTML  = "payment was not successfull! please try again!";
                }
            }

            // show error popup message
            $(errorMessg).show("slow");
            await new Promise(r => setTimeout(r, 2500));
            $(errorMessg).hide("slow");
            return false;

        }

        // enable dorea fund button
        fundCampaign.disabled = false;

    })
})