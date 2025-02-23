let setupCampaign = document.getElementById("setupCampaign");

jQuery(document).ready(async function($) {

    setupCampaign.addEventListener("click", async function (event) {
        event.preventDefault();
        let campaignName = document.getElementById("campaignName");
        let cryptoAmount = document.getElementById("cryptoAmount");
        let shoppingCount = document.getElementById("shoppingCount");
        let errorMessg = document.getElementById("errorMessg");

        if (campaignName.value === "" || cryptoAmount.value === "" || shoppingCount.value === "") {

            errorMessg.innerHTML = "some fields left empty!";

            $(errorMessg).show("slow");
            await new Promise(r => setTimeout(r, 1500));
            $(errorMessg).hide("slow");

            return false;
        }
        else if (/[^A-Za-z0-9 ]/g.exec(campaignName.value)) {
            errorMessg.innerHTML = "Special Characters are not allowed in Campaign Name!";
            $(errorMessg).show("slow");
            await new Promise(r => setTimeout(r, 2500));
            $(errorMessg).hide("slow");
            return false;
        }
        else if (campaignName.value.length >= 25) {
            errorMessg.innerHTML = "no more than 25 length campaign name is allowed! ";
            $(errorMessg).show("slow");
            await new Promise(r => setTimeout(r, 2500));
            $(errorMessg).hide("slow");
            return false;
        } else if (/[a-zA-Z]/.test(cryptoAmount.value) || /[a-zA-Z]/.test(shoppingCount.value)) {

            errorMessg.innerHTML = "amount and shopping counts must be numeric!";
            $(errorMessg).show("slow");
            await new Promise(r => setTimeout(r, 2000));
            $(errorMessg).hide("slow");
            return false;
        } else if (!Number.isInteger(parseInt(cryptoAmount.value)) || !Number.isInteger(parseInt(shoppingCount.value)) ){

            errorMessg.innerHTML = "amount and shopping counts must be integer!";
            $(errorMessg).show("slow");
            await new Promise(r => setTimeout(r, 2000));
            $(errorMessg).hide("slow");
            return false;
        }

        // submit form
        document.getElementById("cashback_campaign").submit();

    });

});