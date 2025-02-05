
// load etherJs library
import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "./ethers.min.js";
import {abi} from "./compile.js";

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

        setTimeout(delay, time)
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
                        url: `${window.location.origin}/wp-admin/admin-ajax.php?_wpnonce=` + _wpnonce,
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