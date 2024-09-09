<x-renderer.card >
    <div id="divCardTitle" class="font-bold text-lg">Create New</div>
    <input id="txtId" class="{{$classList}} hidden" />
    Name: <span class="text-red-400 font-bold">*</span><input id="txtName" class="{{$classList}}" />
    Reg No: <span class="text-red-400 font-bold">*</span> <input id="txtRegNo" class="{{$classList}}" />
    Address: <span class="text-red-400 font-bold">*</span><input id="txtAddress" class="{{$classList}}" />

    <x-renderer.button id="btnCreateNew" htmlType="button" class="mt-2" type='success'>Create New</x-renderer.button>
    
    <x-renderer.button id="btnUpdate" htmlType="button" class="mt-2" type='secondary'>Update</x-renderer.button>
    <x-renderer.button id="btnCancel" htmlType="button" class="mt-2" type='' onClick="cancelUpdate()">Cancel</x-renderer.button>
</x-renderer.card>

<script>
    $(document).ready(()=>{
        const modalId = "{{$modalId}}"
        const isMultiple = !! "{{$multipleStr}}"
        const urlCreateNew = '{{route("erp_vendors.createNewShort")}}';
        const urlUpdate = '{{route("erp_vendors.updateShort")}}';
        const owner_id = {{auth()->id()}};
        const tableName = "{{$tableName}}"
        onDocumentReady(modalId, isMultiple, urlCreateNew, urlUpdate, owner_id, tableName)
    })
</script>