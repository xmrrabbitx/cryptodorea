

let doreaSwitchcCampaign = document.getElementById('doreaSwitchcCampaign');
let doreaCampaignNameSwitch = document.getElementById('doreaCampaignNameSwitch');
let doreaProductCategoriesIcon = document.getElementById("doreaProductCategoriesIcon");
let doreaProductCategoriesList = document.getElementById("doreaProductCategoriesList");
let doreaProductCategoriesArrowDown = document.getElementById("doreaProductCategoriesArrowDown");
let doreaProductCategoriesArrowUp = document.getElementById("doreaProductCategoriesArrowUp");
let doreaProductCategoriesSubmit = document.getElementById("doreaProductCategoriesSubmit");
let doreaProductCategoriesValues = document.querySelectorAll(".doreaProductCategoriesValues");

let categoriesProducts = [];
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
    doreaProductCategoriesValues.forEach(
        (element) => {
            if (element.checked) {
                categoriesProducts.push(element.value);
            }
            element.addEventListener('click', function () {
                if (element.checked) {
                    categoriesProducts.push(element.value);
                } else {
                    categoriesProducts = categoriesProducts.filter(function (letter) {
                        return letter !== element.value;
                    });
                }
            })
        }
    );
    doreaProductCategoriesSubmit.addEventListener("click", async function () {
        if (categoriesProducts.length > 0) {
            jQuery.ajax({
                type: "post",
                url: categoryParams.ajax_url + '?_wpnonce=' + categoryParams.categoryAjaxNonce, data: {
                    action: "dorea_category",
                    data: JSON.stringify({
                        "categories": categoriesProducts,
                    }),
                },
                complete: function (response) {
                    // response on completed!
                },
            });
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