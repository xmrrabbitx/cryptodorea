
let debounceTimeout;

function debounce(func, wait) {
    return function(...args) {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => func.apply(this, args), wait);
    };
}

let dorea_walletaddress = document.getElementById('dorea_walletaddress');
let dorea_add_to_cashback_checkbox = document.querySelectorAll('.dorea_add_to_cashback_checkbox_');
let metamaskError = document.getElementById('dorea_metamask_error');

let campaignlist = [];

dorea_walletaddress.addEventListener('input',function (){
    setTimeout(() => {
        if(dorea_walletaddress.value.length !== 0){
            if(dorea_walletaddress.value.length < 42){
                if (metamaskError.hasChildNodes()) {
                    metamaskError.removeChild(metamaskError.firstChild);
                }
                metamaskError.style.display = 'block';
                dorea_walletaddress.style.border = '1px solid red';
                const errorText = document.createTextNode('please insert a valid wallet address!');
                metamaskError.appendChild(errorText);
                return false;
            }else if(dorea_walletaddress.value.slice(0,2) !=='0x'){
                if (metamaskError.hasChildNodes()) {
                    metamaskError.removeChild(metamaskError.firstChild);
                }
                metamaskError.style.display = 'block';
                dorea_walletaddress.style.border = '1px solid red';
                const errorText = document.createTextNode('wallet address must start with 0x phrase!');
                metamaskError.appendChild(errorText);
                return false;
            }else{
                setSession();
                sessionStorage.setItem('walletAddress', dorea_walletaddress.value);
                metamaskError.style.display = 'none';
                dorea_walletaddress.style.border = '1px solid green';
            }
        }else{
            metamaskError.style.display = 'none';
            dorea_walletaddress.style.border = '1px solid #ccc';
        }
    },1000);
})

dorea_add_to_cashback_checkbox.forEach(

    (element) =>
        element.addEventListener('click', async function(){

            if(element.checked){
                if(!campaignlist.includes(element.value)){
                    campaignlist.push(element.value);
                    setSession();
                }
            }else{
                campaignlist = campaignlist.filter(function (letter) {
                    return letter !== element.value;
                });
            }

        })
)

function setSession(){
    if(campaignlist.length > 0 && dorea_walletaddress.value.length > 0){
        let data = JSON.stringify({'campaignlists':campaignlist,'walletAddress':dorea_walletaddress.value});
        sessionStorage.setItem('doreaCampaignInfo',data);
    }

}


/*
 dorea_add_to_cashback_checked.addEventListener('click',function (){
     console.log('click')
     if(dorea_add_to_cashback_checked.length < 1){

         metamaskError.style.display = 'block';
         dorea_walletaddress.style.border = '1px solid red';
         const errorText = document.createTextNode('please choose one of compaigns!');
         metamaskError.appendChild(errorText);
         return false;
     }else{
         let campaignlist = [];
            for(let i=0; i < dorea_add_to_cashback_checked.length;i++){
                if(dorea_add_to_cashback_checked[i].checked){
                    if(dorea_add_to_cashback_checked[i].value !== ''){
                            campaignlist.push(dorea_add_to_cashback_checked[i].value);
                    }

                }else {

                }
            }
            console.log(campaignlist)
     }
 })

 */

function add_to_cashback_checkbox() {

    let dorea_walletaddress = document.getElementById('dorea_walletaddress');
    const metamaskError = document.getElementById('dorea_metamask_error');

    metamaskError.style.display = 'none';
    dorea_walletaddress.style.border = '1px solid green';
    if(dorea_add_to_cashback_checked.length > 0){

        let campaignlist = [];
        for(let i=0; i < dorea_add_to_cashback_checked.length;i++){
            if(dorea_add_to_cashback_checked[i].checked){
                if(dorea_add_to_cashback_checked[i].value !== ''){
                    campaignlist.push(dorea_add_to_cashback_checked[i].value);
                }

            }else {

            }
        }

        // remove wordpress prefix on production
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '#', true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Content-Type', 'application/json');

        if(campaignlist.length > 0){
            xhr.send(JSON.stringify({'campaignlists':campaignlist,'walletAddress':dorea_walletaddress.value}));
        }

        // Prevent the form from submitting (optional)
        return false;

    }

}

const debouncedAddToCashbackCheckbox = debounce(add_to_cashback_checkbox, 3000);
