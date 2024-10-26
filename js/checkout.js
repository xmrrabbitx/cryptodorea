
let debounceTimeout;

function debounce(func, wait) {
    return function(...args) {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => func.apply(this, args), wait);
    };
}

let dorea_walletaddress = document.getElementById('dorea_walletaddress');
let dorea_add_to_cashback_checkbox = document.querySelectorAll('.dorea_add_to_cashback_checkbox_');
let errorMessg = document.getElementById('dorea_error');

let campaignlist = [];

jQuery(document).ready(async function($) {

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
                    setSession();
                    sessionStorage.setItem('walletAddress', dorea_walletaddress.value);
                    dorea_walletaddress.style.border = '1px solid #ccc';
                }
            } else {
                dorea_walletaddress.style.border = '1px solid #ccc';
            }
        }, 1000);
    })

    dorea_add_to_cashback_checkbox.forEach(
        (element) =>
            element.addEventListener('click', async function () {
                console.log("click event")
                if (element.checked) {
                    if (!campaignlist.includes(element.value)) {
                        campaignlist.push(element.value);
                        setSession();
                        console.log("set session")
                    }
                } else {
                    campaignlist = campaignlist.filter(function (letter) {
                        return letter !== element.value;
                    });
                }

            })
    )

    function setSession() {
        if (campaignlist.length > 0 && dorea_walletaddress.value.length > 0) {
            let data = JSON.stringify({'campaignlists': campaignlist, 'walletAddress': dorea_walletaddress.value});
            sessionStorage.setItem('doreaCampaignInfo', data);
            console.log("session trigger")
        }

    }

    function add_to_cashback_checkbox() {

        let dorea_walletaddress = document.getElementById('dorea_walletaddress');
        const metamaskError = document.getElementById('dorea_metamask_error');

        metamaskError.style.display = 'none';
        dorea_walletaddress.style.border = '1px solid green';
        if (dorea_add_to_cashback_checked.length > 0) {

            let campaignlist = [];
            for (let i = 0; i < dorea_add_to_cashback_checked.length; i++) {
                if (dorea_add_to_cashback_checked[i].checked) {
                    if (dorea_add_to_cashback_checked[i].value !== '') {
                        campaignlist.push(dorea_add_to_cashback_checked[i].value);
                    }

                } else {

                }
            }

            // remove wordpress prefix on production
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '#', true);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('Content-Type', 'application/json');

            if (campaignlist.length > 0) {
                xhr.send(JSON.stringify({'campaignlists': campaignlist, 'walletAddress': dorea_walletaddress.value}));
            }

            // Prevent the form from submitting (optional)
            return false;

        }

    }

    const debouncedAddToCashbackCheckbox = debounce(add_to_cashback_checkbox, 3000);

});