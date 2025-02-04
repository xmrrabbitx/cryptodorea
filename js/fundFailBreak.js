
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
        let _wpnonce = JSON.parse(fundFailBreak)._wpnonce;

        const provider = new BrowserProvider(window.ethereum);

        // Get the signer from the provider metamask
        const signer = await provider.getSigner();

        const contract = new ethers.Contract(contractAddress, abi, signer);

        let balance = await contract.getBalance();
        if(balance >= amount) {
            console.log(balance)
            console.log(amount)
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

    }

    let failBreakReload = document.getElementById("failBreakReload");
    failBreakReload.addEventListener("click", async () => {
        window.location.reload();
    });

});