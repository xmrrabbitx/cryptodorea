

let doreaSwitchcCampaign = document.getElementById('doreaSwitchcCampaign');
let doreaCampaignNameSwitch = document.getElementById('doreaCampaignNameSwitch');

jQuery(document).ready(async function($) {

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
                url: param.ajax_url + '?_wpnonce=' + switchParams.switchAjaxNonce,
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