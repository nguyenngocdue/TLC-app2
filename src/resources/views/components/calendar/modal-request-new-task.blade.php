@extends("modals.modal-medium")
@section($modalId.'-header', "Request to add a new Task")
@section($modalId.'-body')
    <div class="border rounded m-1 p-1">
        <div>
            New Task/Sub-Task Title <span class="text-red-500">*</span>
        </div>
        <input id="txtName" type="text" name="name" class="w-full rounded p-1 border-gray-200">
        <div>
            Request / Explaination <span class="text-red-500">*</span>
        </div>
        <textarea id="txtDescription" name="description" class="w-full rounded p-1 border-gray-200" rows="15"></textarea>
        <div>
            Trace
        </div>
        <input id="modalRequestTxtTitle" type="text" name="trace" readonly class="w-full rounded p-1 border-gray-200 bg-gray-200">
    </div>
@endsection

@section($modalId.'-footer')
<div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
    <x-renderer.button click="closeModal('{{$modalId}}')">Cancel</x-renderer.button>
    <x-renderer.button onClick="submitModal('{{$modalId}}')" id="btnModalRequestSubmit" htmlType="button" class="mx-1" type='success'>Send Request</x-renderer.button>
</div>
@endsection

@section($modalId.'-javascript')
<script>    
    function submitModal(modalId){
        $.ajax({
            url: "/api/v1/entity/it_tickets_createNewShort",
            type: "POST",
            data: {
                name: $("#txtName").val(),
                description: $("#txtDescription").val(),
                status: "assigned",
                assignee_1: 35,
                it_ticket_cat_id: 4,
                it_ticket_sub_cat_id: 2,
                project_id: $("#project_id_11111").val(),
                sub_project_id: $("#sub_project_id_11111").val(),
                discipline_id: $("#discipline_id_11111").val(),
                lod_id: $("#lod_id_11111").val(),
                work_mode_id: $("#work_mode_id_11111").val(),

                owner_id: userCurrent.id,
            },
            success: function(data){
                $(modalId).hide()
                toastr.success("Request has been sent")
            },
            error: function(data){
                $(modalId).hide()
                toastr.error(data.responseJSON.message)
            }
        })
    }

    $(document).ready(()=>{
        const project_id = $("#project_id_11111").val()
        const sub_project_id = $("#sub_project_id_11111").val()
        const discipline_id = $("#discipline_id_11111").val()
        const lod_id = $("#lod_id_11111").val()
        const work_mode_id = $("#work_mode_id_11111").val()

        const project = k["projects"].find(e=>e.id == project_id)
        const sub_project = k["sub_projects"].find(e=>e.id == sub_project_id)
        const discipline = k["user_disciplines"].find(e=>e.id == discipline_id)
        const lod = k["pj_task_phases"].find(e=>e.id == lod_id)
        const work_mode = k["work_modes"].find(e=>e.id == work_mode_id)

        $("#modalRequestTxtTitle").val(`${project.name}(#${project_id}) > ${sub_project.name}(#${sub_project_id}) > ${lod.name}(#${lod_id}) > ${work_mode.name}(#${work_mode_id}) > ${discipline.name}(#${discipline_id})`)
    })
    function checkAnyNull(){
        if( ! $("#txtName").val() ) {
            $("#btnModalRequestSubmit").attr("disabled", true)
            return
        }      
        if( ! $("#txtDescription").val() ) {
            $("#btnModalRequestSubmit").attr("disabled", true)
            return
        }      
        $("#btnModalRequestSubmit").attr("disabled", false)
    }
    $("#txtName").keyup(()=>checkAnyNull())
    $("#txtDescription").keyup(()=>checkAnyNull())    
    checkAnyNull()
</script>
@endsection
