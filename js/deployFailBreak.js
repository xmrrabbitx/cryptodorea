
// load etherJs library
import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "./ethers.min.js";
import {abi} from "./compile.js";

let errorMessg = document.getElementById("errorMessg");


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
        let failedTime = JSON.parse(deployFailBreak).failedTime;
        let _wpnonce = JSON.parse(deployFailBreak)._wpnonce;

        let time;
        if(Date.now() > (failedTime + 20000)){
           time = 0;
        }else {
            time = ((failedTime + 20000) - Date.now());
        }

        setTimeout(delay, time)
        function delay() {
            (async () => {

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
                            url: `${window.location.origin}/wp-admin/admin-ajax.php?_wpnonce=` + _wpnonce,
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
                                sessionStorage.removeItem('deployFailBreak');
                                return false;
                            },
                        });

                        sessionStorage.removeItem('deployFailBreak');

                        return true;
                    }
                });

                errorMessg.innerHTML = "the Contract Deployment was not Successfull! please try again...";
                $(errorMessg).show("slow");
                await new Promise(r => setTimeout(r, 1500));
                $(errorMessg).hide("slow");

                sessionStorage.removeItem('deployFailBreak');

            })();
        }
    }

    let failBreakReload = document.getElementById("failBreakReload");
    failBreakReload.addEventListener("click", async () => {
        window.location.reload();
    });

});