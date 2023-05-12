@once
<script>
    function loadDocs(data){
        console.log("Loading",data)        
        const {docType} = data
        const url = "/api/v1/entity/"+docType+"_renderTable"
        $.ajax({
            type:'POST',
            url,
            data,
            success: (response)=>{
                console.log(response)
                $("#divMain").html(response.hits)
            },
        })
    }
</script>
@endonce

@extends("modals.modal-big")

@section($modalId.'-header', "Document List")

@section($modalId.'-body')
<input id="textToBeLoadedIds" type="hidden" x-bind:value="modalParams['{{$modalId}}']['ids']">
<input id="textToBeLoadedType" type="hidden" x-bind:value="modalParams['{{$modalId}}']['docType']">
<div id="divMain">
    <div class="m-10">
        <i class="fa-duotone fa-spinner fa-spin text-green-500 mx-2"></i>Loading ...
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


