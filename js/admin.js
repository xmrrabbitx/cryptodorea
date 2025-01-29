
// Request access to Metamask
setTimeout(delay, 1000)
function delay(){
    (async () => {
        jQuery(document).ready(async function($) {
            if(sessionStorage.getItem('deployState')){
                sessionStorage.removeItem('deployState');
                // pop up message to inform user that the transaction is expired!
                let trxExpired = document.getElementById("trxExpired");
                $(trxExpired).show('slow');
                await new Promise(r => setTimeout(r, 7000));
                $(trxExpired).hide('slow');
            }
        });
    })();
}