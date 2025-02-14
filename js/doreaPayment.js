

let doreaSwitchcCampaign = document.getElementById('doreaSwitchcCampaign');
let doreaCampaignNameSwitch = document.getElementById('doreaCampaignNameSwitch');
let doreaProductCategoriesIcon = document.getElementById("doreaProductCategoriesIcon");
let doreaProductCategoriesList = document.getElementById("doreaProductCategoriesList");
let doreaProductCategoriesArrowDown = document.getElementById("doreaProductCategoriesArrowDown");
let doreaProductCategoriesArrowUp = document.getElementById("doreaProductCategoriesArrowUp");

jQuery(document).ready(async function($) {

    // list of product categories
    doreaProductCategoriesIcon.addEventListener("click", async function () {
        $(doreaProductCategoriesList).toggle("slow");
        if(doreaProductCategoriesArrowDown.style.display === 'none'){
            $(doreaProductCategoriesArrowDown).show();
            $(doreaProductCategoriesArrowUp).hide();
        }else {
            $(doreaProductCategoriesArrowDown).hide();
            $(doreaProductCategoriesArrowUp).show();
        }
    });

    // switch campaign off/on
    doreaSwitchcCampaign.addEventListener("input", async function () {
        let mode;
        if(doreaSwitchcCampaign.checked){
            mode = 'on';
        }else{
            mode = 'off';
        }

        if(doreaCampaignNameSwitch.name) {

            jQuery.ajax({
                type: "post",
                url: switchParams.ajax_url + '?_wpnonce=' + switchParams.switchAjaxNonce,
                data: {
                    action: "dorea_switchCampaign",
                    data: JSON.stringify({
                        "campaignName": doreaCampaignNameSwitch.name,
                        "mode": mode,
                    }),
                },
                complete: function (response) {

                },
            });
        }
    })

})