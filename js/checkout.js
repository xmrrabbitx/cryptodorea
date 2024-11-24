
let debounceTimeout;

function debounce(func, wait) {
    return function(...args) {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => func.apply(this, args), wait);
    };
}

let doreaClose = document.getElementById('doreaClose');
let doreaCampaignsSection = document.getElementById('doreaCampaignsSection');
let doreaCheckout = document.getElementById('doreaCheckout');
let doreaCheckoutConfirm = document.getElementById('doreaChkConfirm');
let dorea_walletaddress = document.getElementById('dorea_walletaddress');
let dorea_add_to_cashback_checkbox = document.querySelectorAll('.dorea_add_to_cashback_checkbox_');
let errorMessg = document.getElementById('dorea_error');

let campaignlist = [];

jQuery(document).ready(async function($) {

    await new Promise(r => setTimeout(r, 1500));
    $(doreaCheckout).show(2000);

    dorea_walletaddress.addEventListener('input', async function () {
        setTimeout(async () => {
            if (dorea_walletaddress.value.length !== 0) {
                if (dorea_walletaddress.value.length < 42) {
                    errorMessg.innerHTML = "please insert a valid wallet address!";
                    dorea_walletaddress.style.border = '1px solid #f87171';
                    $(errorMessg).show("slow");
                    await new Promise(r => setTimeout(r, 2500));
                    $(errorMessg).hide("slow");
                    return false;
                } else if (dorea_walletaddress.value.slice(0, 2) !== '0x') {
                    errorMessg.innerHTML = "wallet address must start with 0x phrase!";
                    dorea_walletaddress.style.border = '1px solid #f87171';
                    $(errorMessg).show("slow");
                    await new Promise(r => setTimeout(r, 2500));
                    $(errorMessg).hide("slow");
                    return false;
                } else {
                    setValue();
                    dorea_walletaddress.style.border = '1px solid #00b300';
                }
            } else {
                dorea_walletaddress.style.border = '1px solid #ccc'; // grey border on empty wallet address feild
            }
        }, 1000);
    });

    dorea_add_to_cashback_checkbox.forEach(
        (element) =>
            element.addEventListener('click', async function () {
                if (element.checked) {
                    if (!campaignlist.includes(element.value)) {
                        campaignlist.push(element.value);
                        setValue();
                    }
                } else {
                    campaignlist = campaignlist.filter(function (letter) {
                        return letter !== element.value;
                    });
                }
            })
    );

    function setValue() {
        if (campaignlist.length > 0 && dorea_walletaddress.value.length > 0) {
            //sessionStorage.setItem("doreaCampaignlist", campaignlist);
            //sessionStorage.setItem("doreaWalletaddress", dorea_walletaddress.value);
            jQuery.ajax({
                type: "post",
                url: `${window.location.origin}/wp-admin/admin-ajax.php`,
                data: {
                    action: "dorea_ordered_received",  // the action to fire in the server
                    data: JSON.stringify({
                        "campaignlists":campaignlist,
                        "walletAddress":dorea_walletaddress.value,
                    }),
                },
                complete: function (response) {
                    // response on completed!
                },
            });
        }

    }

    doreaCheckoutConfirm.addEventListener('click', async function () {
        if(campaignlist.length < 1 ){
            errorMessg.innerHTML = "you should choose at least one campaign!";
            doreaCampaignsSection.style.border = '1px solid #f87171'; // red border
            $(errorMessg).show("slow");
            await new Promise(r => setTimeout(r, 2500));
            $(errorMessg).hide("slow");
            doreaCampaignsSection.style.border = '1px solid #ccc'; // back to grey border
            return false;
        }else if(dorea_walletaddress.value.length < 1 ){
            errorMessg.innerHTML = "you should insert your wallet address!";
            dorea_walletaddress.style.border = '1px solid #f87171'; // red border
            $(errorMessg).show("slow");
            await new Promise(r => setTimeout(r, 2500));
            $(errorMessg).hide("slow");
            dorea_walletaddress.style.border = '1px solid #ccc'; // back to grey border
            return false;
        }else{
            $(doreaCheckout).hide("slow");
        }
    });

    doreaClose.addEventListener("click", async function () {
        await new Promise(r => setTimeout(r, 100));
        $(doreaCheckout).hide("slow");
    });


});