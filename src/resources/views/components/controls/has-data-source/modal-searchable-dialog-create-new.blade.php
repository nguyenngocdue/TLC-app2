<x-renderer.card title="Create New">
    Reg No: <span class="text-red-400 font-bold">*</span> <input id="txtRegNo" class="{{$classList}}" />
    Name: <span class="text-red-400 font-bold">*</span><input id="txtName" class="{{$classList}}" />
    Address: <span class="text-red-400 font-bold">*</span><input id="txtAddress" class="{{$classList}}" />

    <x-renderer.button id="btnCreateNew" htmlType="button" class="mt-2" type='success'>Create New</x-renderer.button>
</x-renderer.card>

<script>
    
    function validateForm(){
        const regNo = $('#txtRegNo').val();
        const name = $('#txtName').val();
        const address = $('#txtAddress').val();
        
        const isValidated = (!regNo || !name || !address);
        $("#btnCreateNew").prop('disabled', isValidated);
    }
    $(document).ready(()=>{
        const modalId = "{{$modalId}}"
        $("#txtRegNo, #txtName, #txtAddress").on('input', function(){
            validateForm();
        })
        $("#btnCreateNew").on('click', function(){
            const url = '{{route("erp_vendors.createNewShort")}}';
            console.log(url);
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

                        const seletectValueCtrl = $("#"+modalId+"_selectedValue")
                        const seletectValueCtrlVal = seletectValueCtrl.val();
                        const newIdStrs = (seletectValueCtrlVal == "")?(insertedId): seletectValueCtrlVal + "," + insertedId;

                        seletectValueCtrl.val(newIdStrs);
                        modalSearchableDialogOnSelectHandleText(modalId, newIdStrs)
                        //Reset keyword and reload the table lines
                        $("#"+modalId+"_txtName").val("").trigger('keyup');
                        toastr.success('Vendor created successfully');
                    }
                },
                error: function(e){
                    toastr.error(e.responseJSON.message);
                }
            })
        })
        validateForm();
    })
</script>