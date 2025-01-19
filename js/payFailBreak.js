
// load etherJs library
import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "./ethers.min.js";
import {abi} from "./compile.js";

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

    let payFailBreak = sessionStorage.getItem('payFailBreak');
    console.log(payFailBreak)
    if(payFailBreak){
        let contractAddress = params.contractAddress;
        let campaignName = JSON.parse(payFailBreak).campaignName;

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
                action: "dorea_pay",
                data: JSON.stringify({
                    "userList":params.usersList,
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
                sessionStorage.removeItem('payFailBreak');
                return false;
            },
        });

        sessionStorage.removeItem('payFailBreak');

    }

    let failBreakReload = document.getElementById("failBreakReload");
    failBreakReload.addEventListener("click", async () => {
        window.location.reload();
    });

});