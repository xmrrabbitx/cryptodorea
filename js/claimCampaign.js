
import {
    ethers,
    BrowserProvider
} from "./ethers.min.js";


let deleteCampaignModal = document.querySelectorAll(".doreaClaim");

deleteCampaignModal.forEach(

    (element) =>

        element.addEventListener("click", async function(){

            let contractAddress = element.value.split("_")[0] ?? null;
            let walletAddress = element.value.split("_")[1] ?? null;
            let amount =  element.value.split("_")[2] ?? null;
            let _encValue = element.value.split("_")[3] ?? null;
            let _encMessage = element.value.split("_")[4] ?? null;

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


            await window.ethereum.request({ method: "eth_requestAccounts" });
            const accounts = await ethereum.request({ method: "eth_accounts" });

            if (window.ethereum) {

                const userAddress = accounts[0];

                const userBalance = await window.ethereum.request({
                    method: "eth_getBalance",
                    params: [userAddress, "latest"]
                });

                // check balance of metamask wallet
                if(parseInt(userBalance) < 300000000000000){


                    let err = "not enough balance to support fee! \n please fund your wallet at least 0.0003 ETH!";
                    Toastify({
                        text: err,
                        duration: 3000,
                        style: {
                            background: "#ff5d5d",
                        },
                    }).showToast();
                    return false;
                }

                const provider = new BrowserProvider(window.ethereum);

                const signer = await provider.getSigner();

                let message = "Dorea Cashback: you are claiming your cashback now!";

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

                console.log(messageHash)
                console.log(r)
                console.log(s)
                console.log(v)

                /*
                let cryptoAmountBigInt = [];
                for(const amount of amounts){

                    if((typeof(amount) === "number") && (Number.isInteger(amount))){

                        const creditAmountBigInt = BigInt(amount);
                        const multiplier = BigInt(1e18);
                        cryptoAmountBigInt.push((creditAmountBigInt * multiplier).toString());

                    }
                    else{

                        const creditAmount = amount; // This is a floating-point number
                        const multiplier = BigInt(1e18); // This is a BigInt
                        const factor = 1e18;

                        // Convert the floating-point number to an integer
                        const creditAmountInt  = BigInt(Math.round(creditAmount * factor));
                        cryptoAmountBigInt.push((creditAmountInt * multiplier / BigInt(factor)).toString());
                    }

                }
                */
                //console.log(parseInt(amount))
                console.log(_encValue)
                //console.log(_encMessage.toString())
                if(amount !== null){
                    try{

                        const contract = new ethers.Contract(contractAddress, ' . $abi . ',signer);

                        await contract.pay(
                            walletAddress,
                            parseInt(amount),
                            _encValue.toString(),
                            _encMessage.toString(),
                            messageHash,
                            v,
                            r,
                            s
                        ).then(async function(response){
                            response.wait().then(async (receipt) => {
                                // transaction on confirmed and mined
                                if (receipt) {
                                    let succMessage = "payment has been successfull!";
                                    Toastify({
                                        text: succMessage,
                                        duration: 3000,
                                        style: {
                                            background: "linear-gradient(to right, #32DC98, #2EC4A1)",
                                        },
                                    }).showToast();

                                    await new Promise(r => setTimeout(r, 1500));
                                    let balance = await contract.getBalance();
                                    balance = convertWeiToEther(parseInt(balance));

                                    // get contract address
                                    let xhr = new XMLHttpRequest();

                                    // remove wordpress prefix on production
                                    xhr.open("POST", "/wordpress/wp-admin/admin-post.php?action=dorea_claimed_cashback", true);
                                    xhr.onreadystatechange = async function() {
                                        if (xhr.readyState === 4 && xhr.status === 200) {

                                            window.location.reload();
                                        }
                                    }


                                }
                            });
                        });


                    }catch (error) {

                        console.log(error)
                        // reload on any error
                        // get contract address
                        let xhr = new XMLHttpRequest();

                        // remove wordpress prefix on production
                        xhr.open("POST", "/wordpress/wp-admin/admin-post.php?action=dorea_claimed_cashback", true);
                        xhr.onreadystatechange = async function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {

                                // window.location.reload();
                            }
                        }
                        xhr.send(JSON.stringify({"test":"testing!"}));

                    }
                }

            }
        })
)