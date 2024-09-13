<?php

/**
 * Delete Campaign Modal
 */

function deleteModal():bool
{
    return print ('
        <script>
            
                let deleteCampaignModal = document.querySelectorAll(".deleteCampaign_");

                deleteCampaignModal.forEach(
                
                    (element) =>             
               
                     element.addEventListener("click", function(){
           
                        let deleteModal = document.getElementById("deleteModal");
                        let url = element.getAttribute("name");    
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
                    
                     })  
                )
        </script>
       
       <!-- delete campaign modal -->
        <div id="deleteModal" class="!fixed !mx-auto !left-0 !right-0 !top-[20%] !bg-white !w-96 shadow-[0_5px_25px_-15px_rgba(0,0,0,0.3)] !p-10 !rounded-md !text-center !border" style="display: none">
            <p class="!text-base">Are You Sure?</p>
            <div class="!mt-5">
                <button id="cancelDeleteCampaign" class="">Cancel</button>
                <button id="DeleteCampaignConfirm" class="!bg-[#faca43] !p-[9px] !ml-5 !rounded-md">Delete</button>
            </div>
        </div>
        
    ');
}

