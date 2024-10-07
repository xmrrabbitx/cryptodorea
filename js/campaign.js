let setupCampaign = document.getElementById("setupCampaign");
setupCampaign.addEventListener("click", function(event){
    event.preventDefault();
    let campaignName = document.getElementById("campaignName");
    let cryptoAmount = document.getElementById("cryptoAmount");
    let shoppingCount = document.getElementById("shoppingCount");
    let errorMessg = document.getElementById("errorMessg");

    if(campaignName.value === "" || cryptoAmount.value === "" || shoppingCount.value === ""){

        errorMessg.innerHTML = "some fields left empty!";
        jQuery(document).ready(async function($){
            $(errorMessg).show("slow");
            await new Promise(r => setTimeout(r, 1500));
            $(errorMessg).hide("slow");
        });
        return false;
    }else if(/[a-zA-Z]/.test(cryptoAmount.value) || /[a-zA-Z]/.test(shoppingCount.value)){

        errorMessg.innerHTML = "amount and shopping counts must be numeric!";
        jQuery(document).ready(async function($){
            $(errorMessg).show("slow");
            await new Promise(r => setTimeout(r, 1500));
            $(errorMessg).hide("slow");
        });
        return false;
    }

    // submit form
    document.getElementById("cashback_campaign").submit();

});