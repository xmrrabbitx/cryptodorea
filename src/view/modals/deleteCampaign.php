<?php

/**
 * Delete Campaign Modal
 */

function deleteModal(): int
{
    return print ('
        <script>
            let deleteButt = document.getElementById("deleteCampaign");

             deleteButt.addEventListener("click", async () => {
                  let deleteModal = document.getElementById("deleteModal");
                  let url = deleteButt.getAttribute("name");    
                  deleteModal.style.display = "block";
                  
                let cancelDeleteButt = document.getElementById("cancelDeleteCampaign");
                let conformDeleteButt = document.getElementById("DeleteCampaignConfirm");
                
                cancelDeleteButt.addEventListener("click", async () => {
                    let deleteModal = document.getElementById("deleteModal");
                    deleteModal.style.display = "none";
                  
                });
                
                conformDeleteButt.addEventListener("click", async () => {
                    
                    window.location.replace(url);
                  
                });
                
             });
                

        </script>
       
        <div id="deleteModal" class="!absolute !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-10 !rounded-md !text-center" style="display: none">
            <p class="!text-base">Are You Sure?</p>
            <div class="!mt-5">
                <button id="cancelDeleteCampaign" class="">Cancel</button>
                <button id="DeleteCampaignConfirm" class="!bg-[#faca43] !p-[9px] !ml-5 !rounded-md">Delete</button>
            </div>
        </div>
        
    ');
}

