let campaignInfo = JSON.parse(sessionStorage.getItem("doreaCampaignInfo"));

// remove wordpress prefix on production
let xhr = new XMLHttpRequest();
xhr.open("POST", "#", true);
xhr.setRequestHeader("Accept", "application/json");
xhr.setRequestHeader("Content-Type", "application/json");

xhr.onreadystatechange = async function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        sessionStorage.removeItem("doreaCampaignInfo");
        sessionStorage.removeItem('doreaTimer');
    }
}
if(campaignInfo !== null){
    xhr.send(JSON.stringify({"campaignlists":campaignInfo.campaignlists,"walletAddress":campaignInfo.walletAddress}));
}