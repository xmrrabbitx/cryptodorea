<?php

/**
 * the payment modal for admin campaigns
 */
//('admin_menu', 'dorea_campaign_pay');
function dorea_campaign_pay(): void
{
    print('<script>

         let campaignNames = document.querySelectorAll(".campaignPayment_");
        
         console.log(campaignNames)
         campaignNames.forEach(
                
            (element) =>             
           
              element.addEventListener("click", function(){
                        
                     
            })
         )
                   
    </script>');
}