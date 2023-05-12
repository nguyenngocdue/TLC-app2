@once
<script>
    function loadDocs(data){
        // console.log("Loading",data)        
        const {docType} = data
        const url = "/api/v1/entity/"+docType+"_renderTable"
        $.ajax({
            type:'POST',
            url,
            data,
            success: (response)=>{
                $("#divMain").html(response.hits)
            },
            error:(response)=>{
                // console.log(response.responseJSON.message)
                const message = response.responseJSON.message
                $("#divMain").html("<b class='text-red-500'>"+message+"</b>")
            }
        })
    }
</script>
@endonce

@extends("modals.modal-big")

@section($modalId.'-header', "Document List")

@section($modalId.'-body')
<input id="textToBeLoadedIds" type="hidden" x-bind:value="modalParams['{{$modalId}}']['ids']">
<input id="textToBeLoadedType" type="hidden" x-bind:value="modalParams['{{$modalId}}']['docType']">
<div id="divMain" class="m-2 text-center">
    <div class="my-60">
        <i class="fa-duotone fa-spinner fa-spin text-green-500 mx-2"></i>Loading <span x-html="modalParams['{{$modalId}}']['count']"></span> item(s)...
    </div>
</div>
@endsection

@section($modalId.'-footer')
@endsection

@section($modalId.'-javascript')
<script>
    setTimeout(()=>{
        const ids = $("#textToBeLoadedIds").val()
        const docType = $("#textToBeLoadedType").val()

        loadDocs({ids, docType})
    }, 100)
</script>
@endsection


