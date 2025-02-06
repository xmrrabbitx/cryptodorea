/**
 * load etherjs library
 * URL: https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.min.js
 * Source Code: https://github.com/ethers-io/ethers.js
 */
import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "./etherv67.min.js";
import {abi} from "./doreaCompile.js";

let errorMessg = document.getElementById("errorMessg");


jQuery(document).ready(async function($) {

    function convertWeiToEther(amount){

        const creditAmountBigInt = amount;
        const multiplier = 1e18;
        return creditAmountBigInt / multiplier;

    }

    let deployFailBreak = localStorage.getItem('deployFailBreak');
    if(deployFailBreak){
        let contractAddress = JSON.parse(deployFailBreak).contractAddress;
        let campaignName = JSON.parse(deployFailBreak).campaignName;
        let failedTime = JSON.parse(deployFailBreak).failedTime;
        let _wpnonce = JSON.parse(deployFailBreak)._wpnonce;

        let time;
        if(Date.now() > (failedTime + 20000)){
           time = 0;
        }else {
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
        if(timerStatus) {
            for(timer;timer >= 0;){
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

                async function isContract(contractAddress) {
                    const code = await provider.getCode(contractAddress);
                    return code !== "0x"; // Returns true if a contract exists, false otherwise
                }

                isContract(contractAddress).then(async (status) => {
                    if (status === true) {
                        const contract = new ethers.Contract(contractAddress, abi, signer);

                        let balance = await contract.getBalance();
                        balance = convertWeiToEther(parseInt(balance));

                        jQuery.ajax({
                            type: "post",
                            url: param.ajax_url + '?_wpnonce=' + _wpnonce,
                            data: {
                                action: "dorea_contract_address",  // the action to fire in the server
                                data: JSON.stringify({
                                    "contractAddress": contractAddress,
                                    "contractAmount": balance,
                                    "campaignName": campaignName
                                }),
                            },
                            complete: async function (response) {

                                // pop up message to reload the  page after interrupt transaction
                                let failBreakModal = document.getElementById("failBreakModal");
                                $(failBreakModal).show("slow");
                                localStorage.removeItem('deployFailBreak');
                                return false;
                            },
                        });

                        localStorage.removeItem('deployFailBreak');

                        return true;
                    }
                });

                errorMessg.innerHTML = "the Contract Deployment probably was not Successfull! please try again...";
                $(errorMessg).show("slow");
                await new Promise(r => setTimeout(r, 1500));
                $(errorMessg).hide("slow");

                localStorage.removeItem('deployFailBreak');

            })();
        }
    }

    let failBreakReload = document.getElementById("failBreakReload");
    failBreakReload.addEventListener("click", async () => {
        window.location.reload();
    });

});