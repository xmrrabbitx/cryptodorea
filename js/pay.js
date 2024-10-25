
// load etherJs library
import {ethers, BrowserProvider, ContractFactory, formatEther, formatUnits, parseEther, Wallet} from "./ethers.min.js";

import {abi} from "./compile.js";

let payCampaign = document.getElementById("dorea_pay");
let errorMessg = document.getElementById("dorea_metamask_error");

payCampaign.addEventListener("click", async function(){
    console.log("pay trigger")
            function convertToWei(amounts){

                let amountsBig = [];
                amounts.forEach(
                  (amount) => {
                      if ((typeof (amount) === "number") && (Number.isInteger(amount))) {

                          const creditAmountBigInt = BigInt(amount);
                          const multiplier = BigInt(1e18);
                          const result = creditAmountBigInt * multiplier;
                          amountsBig.push(parseInt(result));

                      }else{

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

            function convertWeiToEther(amount){

                const creditAmountBigInt = amount;
                const multiplier = 1e18;
                return creditAmountBigInt / multiplier;

            }

            let payCampaignVal = payCampaign.id;
            const contractAddress = param.contractAddress;
            let campaignName = param.campaignName;

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

            let message = "you are siging message to fund the contract" + param.campaignName;

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

            let amounts  = convertToWei(param.qualifiedUserEthers);

            let userAddresses = param.qualifiedWalletAddresses;

            let amountsSum = amounts.reduce((accumulator, currentValue) => {
                return parseInt(accumulator) + parseInt(currentValue)
            },0);

            console.log(
                messageHash,
                r,
                s,
                v,
                amounts,
                userAddresses,
                contractAddress
            )

            try{

                const contract = new ethers.Contract(contractAddress, abi, signer);

                let balance = await contract.getBalance();

                if(balance < amountsSum){

                    errorMessg.innerHTML = "the campaign reached to the end!";
                    $(errorMessg).show("slow");
                    await new Promise(r => setTimeout(r, 2500));
                    $(errorMessg).hide("slow");

                }

                await contract.pay(
                    JSON.parse(userAddresses),
                    amounts,
                    messageHash,
                    v,
                    r,
                    s
                ).then(async function(response){
                    response.wait().then(async (receipt) => {
                        // transaction on confirmed and mined
                        if (receipt) {
                            console.log(receipt)
                            let balance = await contract.getBalance();
                            balance = convertWeiToEther(parseInt(balance));

                            successMessg.innerHTML = "payment has been successfull!";
                            $(successMessg).show("slow");
                            await new Promise(r => setTimeout(r, 1500));
                            $(successMessg).hide("slow");


                            let xhr = new XMLHttpRequest();

                            // remove wordpress prefix on production
                            xhr.open("POST", "#", true);
                            xhr.onreadystatechange = async function() {
                                if (xhr.readyState === 4 && xhr.status === 200) {

                                    // window.location.reload();
                                    $(claimContainer).hide("slow");
                                }
                            }

                            xhr.send(JSON.stringify({"claimCampaign":{"amountWei":amount, 'balance':balance, "campaignName":campaignName,"totalPurchases":totalPurchases,"claimedAmount":amountEther}}));
                        }
                    });
                });


            }catch (error) {
                console.log(error)
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
        }
)
