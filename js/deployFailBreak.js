
// load etherJs library
import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "./ethers.min.js";
import {abi} from "./compile.js";


jQuery(document).ready(async function($) {

    function convertWeiToEther(amount){

        const creditAmountBigInt = amount;
        const multiplier = 1e18;
        return creditAmountBigInt / multiplier;

    }

    let deployFailBreak = sessionStorage.getItem('deployFailBreak');
    if(deployFailBreak){
        let contractAddress = JSON.parse(deployFailBreak).contractAddress;
        let campaignName = JSON.parse(deployFailBreak).campaignName;

        const provider = new BrowserProvider(window.ethereum);

        // Get the signer from the provider metamask
        const signer = await provider.getSigner();

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
            complete: async function (response) {

                // pop up message to reload the  page after interrupt transaction
                let failBreakModal = document.getElementById("failBreakModal");
                $(failBreakModal).show("slow");

            },
        });

        sessionStorage.removeItem('deployFailBreak');

    }

    let failBreakReload = document.getElementById("failBreakReload");
    failBreakReload.addEventListener("click", async () => {
        window.location.reload();
    });

});