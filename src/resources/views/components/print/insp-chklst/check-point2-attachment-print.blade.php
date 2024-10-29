@if(!$checkpoint->insp_photos->isEmpty())
    <x-renderer.attachment-group 
        openType="_blank" 
        name="attachment" 
        readOnly=1 
        destroyable=0 
        showToBeDeleted=0 
        showUploadFile=0 
        
        :groups="$attachmentGroups" 
        :value="$checkpoint->insp_photos" 
        />
@endif