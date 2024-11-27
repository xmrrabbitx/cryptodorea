
jQuery(document).ready(async function($) {

    let dorea_campaigns_checkout_title = await document.getElementById('dorea_campaigns_checkout_title_field');
    let dorea_campaigns_checkout = await document.getElementById("dorea_campaigns_checkout");


    dorea_campaigns_checkout_title.addEventListener("click", async function (event) {
        event.preventDefault();
        if (dorea_campaigns_checkout_title.checked) {
            $(dorea_campaigns_checkout).show(2000);
        } else {
            $(dorea_campaigns_checkout).hide(2000);
        }
    });

});