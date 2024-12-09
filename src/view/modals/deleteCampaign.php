<?php

/**
 * Delete Campaign Modal
 */
function deleteModal():bool
{
    // load claim campaign scripts
    wp_enqueue_script('DOREA_DELETECAMPAIGN_SCRIPT', plugins_url('/cryptodorea/js/deleteCampaign.js'), array('jquery', 'jquery-ui-core'));

    return print ('
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

