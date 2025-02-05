
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