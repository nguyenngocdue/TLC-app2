
{{-- @extends("modals.modal-large") --}}
@extends("modals.modal-medium")
{{-- @extends("modals.modal-small") --}}

@section($modalId.'-header', "Edit Task")

@section($modalId.'-body')
    <input id="txtTaskId" type="hidden" x-bind:value="modalParams['{{$modalId}}']['id']">
    <div id="divTaskBody" class="my-4"></div>
@endsection

@section($modalId.'-footer')
<div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
    <x-renderer.button click="closeModal('{{$modalId}}')">Cancel</x-renderer.button>
    <x-renderer.button class="mx-2" type='success' icon="fa-duotone fa-floppy-disk">Save</x-renderer.button>
</div>
@endsection

@section($modalId.'-javascript')
<script>
    setTimeout(() => {
        const id = $("#txtTaskId").val()
        console.log("Start up ", id)
        $.ajax({
            method:"POST",
            url: route_task,
            data: { action:"getItemRenderProps", id,},
            success: function (response) {
                const {renderer} = response.hits
                $("#divTaskBody").html(renderer)
            //   console.log(response)
            },
            error: function (jqXHR) {
                toastr.error(jqXHR.responseJSON.message)
            },
        })
    }, 100);
</script>
@endsection