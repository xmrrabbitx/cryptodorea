
// load etherJs library
import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "./ether.min.js";
import {abi} from "./doreaCompile.js";

jQuery(document).ready(async function($) {

    function convertWeiToEther(amount){

        const creditAmountBigInt = amount;
        const multiplier = 1e18;
        return creditAmountBigInt / multiplier;

    }

    let fundFailBreak = localStorage.getItem('fundFailBreak');

    if(fundFailBreak){
        let contractAddress = params.contractAddress;
        let campaignName = JSON.parse(fundFailBreak).campaignName;
        let amount = JSON.parse(fundFailBreak).amount;
        let failedTime = JSON.parse(fundFailBreak).failedTime;
        let _wpnonce = JSON.parse(fundFailBreak)._wpnonce;

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

                const contract = new ethers.Contract(contractAddress, abi, signer);

                let balance = await contract.getBalance();
                if(balance >= amount) {

                    balance = convertWeiToEther(parseInt(balance));

                    jQuery.ajax({
                        type: "post",
                        url: param.ajax_url + '?_wpnonce=' + _wpnonce,
                        data: {
                            action: "dorea_fund",
                            data: JSON.stringify({
                                "balance": balance,
                                "campaignName": campaignName,
                            }),
                        },
                        complete: function (response) {
                            // pop up message to reload the  page after interrupt transaction
                            let failBreakModal = document.getElementById("failBreakModal");
                            $(failBreakModal).show("slow");
                            localStorage.removeItem('fundFailBreak');
                            return false;
                        },
                    });

                }

                localStorage.removeItem('fundFailBreak');
            })();
        }
    }

    let failBreakReload = document.getElementById("failBreakReload");
    failBreakReload.addEventListener("click", async () => {
        window.location.reload();
    });

});