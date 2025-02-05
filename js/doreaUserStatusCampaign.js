
let claimContainer = document.getElementById("doreaClaimModal");
let claimError = document.getElementById("doreaClaimError");
let closeCampaignModal = document.getElementById("doreaCloseModal");
let closeCampaignError = document.getElementById("doreaCloseError");

jQuery(document).ready(async function($) {

    let dorea_cashbback_menu = document.querySelector('a[href*="dorea_cashbback_menu"]');

    if(dorea_cashbback_menu) {

        // show modal on sidebar menu trigger
        dorea_cashbback_menu.addEventListener('click', function (event) {
            event.preventDefault();

            if(claimContainer) {

                $(claimContainer).show(2500);
            }else {

                $(claimError).show(2500);

            }
        });
    }

    if(closeCampaignModal) {
        closeCampaignModal.addEventListener("click", async function () {
            await new Promise(r => setTimeout(r, 100));
            $(claimContainer).hide("slow");
        });
    }

    if(closeCampaignError) {
        closeCampaignError.addEventListener("click", async function () {
            await new Promise(r => setTimeout(r, 100));
            $(claimError).hide("slow");
        });
    }
});
