
{{-- @extends("modals.modal-large") --}}
@extends("modals.modal-medium")
{{-- @extends("modals.modal-small") --}}

@section($modalId.'-header', "Header")

@section($modalId.'-body')
    <input id="txtTaskId" type="hidden1" x-bind:value="modalParams['{{$modalId}}']['id']">
    <div class="p-2">
        Task Body
    </div>
    <div id="divTaskBody"></div>
@endsection

{{-- @section($modalId.'-footer')
@endsection --}}

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