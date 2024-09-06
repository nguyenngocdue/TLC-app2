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
    function prepareForUpdate(hitId){
        console.log(hitId);
        const hit = modalSearchableDialogHits[hitId];
        $('#txtId').val(hit.id);
        $('#txtRegNo').val(hit.reg_no);
        $('#txtName').val(hit.name);
        $('#txtAddress').val(hit.address);
        modalSearchableDialogMode = 'update';        
        validateForm();
    }
    function cancelUpdate(){
        $('#txtRegNo, #txtName, #txtAddress').val('');
        modalSearchableDialogMode = 'createNew';        
        validateForm();
    }
    function validateForm(){
        const regNo = $('#txtRegNo').val();
        const name = $('#txtName').val();
        const address = $('#txtAddress').val();
        
        const isValidated = (!regNo || !name || !address);
        $("#btnCreateNew").prop('disabled', isValidated);
        $("#btnUpdate").prop('disabled', isValidated);

        switch(modalSearchableDialogMode){
            case 'createNew':
                $("#btnUpdate").hide();
                $("#btnCancel").hide();
                $("#btnCreateNew").show();
                $("#divCardTitle").text('Create New');
                break;
            case 'update':
                $("#btnUpdate").show();
                $("#btnCancel").show();
                $("#btnCreateNew").hide();
                $("#divCardTitle").text('Update');
                break;
        }
    }
    $(document).ready(()=>{
        const modalId = "{{$modalId}}"
        $("#txtRegNo, #txtName, #txtAddress").on('input', function(){
            validateForm();
        })
        const sendAjax = (type) => {
            let url = ''
            if(type == 'createNew') {
                url = '{{route("erp_vendors.createNewShort")}}';
                $.ajax({
                    method: 'POST',
                    url,
                    data:{
                        reg_no: $('#txtRegNo').val(),
                        name: $('#txtName').val(),
                        address: $('#txtAddress').val(),
                        owner_id: {{auth()->id()}},           
                    },
                    success: function(response){
                        if(response.success){
                            console.log(response);
                            const hit = response.hits;
                            const insertedId = hit.id
                            modalSearchableDialogHits[insertedId] = hit;
    
                            $('#txtRegNo, #txtName, #txtAddress').val('');
                            validateForm();
    
                            const seletectValueCtrl = getEById(modalId+"_selectedValue")
                            const seletectValueCtrlVal = seletectValueCtrl.val();
                            const newIdStrs = (seletectValueCtrlVal == "")?(insertedId): seletectValueCtrlVal + "," + insertedId;
    
                            seletectValueCtrl.val(newIdStrs);
                            //Reset keyword and reload the table lines
                            getEById(modalId+"_txtName").val("").trigger('keyup');
                            toastr.success('Vendor created successfully');
                                                        
                                                       
                            
                        }
                    },
                    error: function(e){
                        toastr.error(e.responseJSON.message);
                    }
                })
            }
            if(type == 'update') {
                url = '{{route("erp_vendors.updateShort")}}';
                const id = $('#txtId').val();
                $.ajax({
                    method: 'POST',
                    url,
                    data:{
                        lines:[
                            { id, fieldName: 'reg_no', value: $('#txtRegNo').val() },
                            { id, fieldName: 'name', value: $('#txtName').val() },
                            { id, fieldName: 'address', value: $('#txtAddress').val() },
                        ] 
                    },
                    success: function(response){
                        if(response.success){
                            console.log(response.hits);

                            $('#txtRegNo, #txtName, #txtAddress').val('');
                            modalSearchableDialogMode = 'createNew';
                            validateForm();
                            
                            getEById(modalId+"_txtName").val("").trigger('keyup');
                            toastr.success('Vendor updated successfully');
                            console.log(modalSearchableDialogHits)

                            // modalSearchableDialogOnSelectHandleText(modalId)                                
                        }
                    },
                    error: function(e){
                        toastr.error(e.responseJSON.message);
                    }
                })
            }
        }
        $("#btnCreateNew").on('click', ()=>sendAjax('createNew'))
        $("#btnUpdate").on('click', ()=>sendAjax('update'))
        validateForm();
    })
</script>