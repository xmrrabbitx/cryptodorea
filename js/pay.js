
// load etherJs library
import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "./ethers.min.js";

import {abi} from "./compile.js";

let campaignNames = document.querySelectorAll(".campaignPayment_");
const metamaskError = document.getElementById("dorea_metamask_error");

campaignNames.forEach(

    (element) =>

        element.addEventListener("click", async function(){

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

            let elmentIed = element.id;
            const contractAddress = elmentIed.split("_")[3];
            let campaignNameFirst = elmentIed.split("_")[1];
            let campaignNameSecond = elmentIed.split("_")[2];
            let campaignName = campaignNameFirst + "_" + campaignNameSecond;
            let paymentStatus = elmentIed.split("_")[4];

            /*
             await window.ethereum.request({
                                      method: "wallet_addEthereumChain",
                                      params: [{
                                        chainId: "0x14A34",
                                        rpcUrls: ["https://base-sepolia.blockpi.network/v1/rpc/public"],
                                        chainName: "SEPOLIA",
                                        nativeCurrency: {
                                          name: "ETH",
                                          symbol: "ETH",
                                          decimals: 18
                                        },
                                        blockExplorerUrls: ["https://base-sepolia.blockscout.com"]
                                      }]
             });

             */


            await window.ethereum.request({ method: "eth_requestAccounts" });
            const accounts = await ethereum.request({ method: "eth_accounts" });
            const account = accounts[0];

            const provider = new BrowserProvider(window.ethereum);

            const signer = await provider.getSigner();

            let message = "you are siging message to fund the contract!";

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

            let fundAgainAmount = convertToWei(OBJECT.remainingAmount);

            try{

                const contract = new ethers.Contract(contractAddress, abi, signer)

                // check if transaction exceeds the contract balance
                const balance = await contract.getBalance();
                console.log(balance)
                if(fundAgainAmount > balance){

                    metamaskError.style.display = "block";
                    const errorText = document.createTextNode("Sorry, the campaign has not enough fund!");
                    metamaskError.appendChild(errorText);
                    return false;

                }

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
                        response.wait().then(async (receipt) => {
                            // transaction on confirmed and mined
                            if (receipt) {
                                let succMessage = "payment has been successfull!";


                                await new Promise(r => setTimeout(r, 1500));

                                let balance = await contract.getBalance();
                                balance = convertWeiToEther(parseInt(balance));

                                // get contract address
                                let xhr = new XMLHttpRequest();

                                // remove wordpress prefix on production
                                xhr.open("POST", "/wordpress/wp-admin/admin-post.php?action=dorea_new_contractBalance", true);
                                xhr.onreadystatechange = async function() {
                                    if (xhr.readyState === 4 && xhr.status === 200) {

                                        window.location.reload();
                                    }
                                }

                                xhr.send(JSON.stringify({"balance":JSON.stringify(balance),"campaignName":campaignName}));

                            }
                        });

                })


            }catch (error) {
                if(typeof error.revert === "undefined")   {
                    // "Something went wrong. please try again!"
                }else{
                    let errorMessg = error.revert.args[0];
                    if(errorMessg === "Insufficient balance"){
                        errorMessg = "Insufficient balance";

                    }else if(errorMessg === "User is not Authorized!!!"){
                        errorMessg = "You dont have permission to pay!";

                    }else{
                        errorMessg = "payment was not successfull! please try again!";

                    }
                }

                // show error popup message
                //metamaskError.style.display = "block";
                //metamaskError.innerHTML = errorMessg;
                return false;

            }
        })
)
