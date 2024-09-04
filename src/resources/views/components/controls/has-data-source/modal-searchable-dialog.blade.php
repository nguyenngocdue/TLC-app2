@extends("modals.modal-medium")
@section($modalId.'-header', "Select XXX")
@section($modalId.'-body')
    <div class="border rounded m-1 p-1">
        <div>
            Keywords:<span class="text-red-500">*</span>
        </div>
        <input id="{{$modalId}}_txtName" type="text" name="name" class="w-full rounded p-1 border-gray-200"/>
        Results:
        <p>1</p>
        <p>2</p>
        <p>3</p>
        <p>4</p>
        <p>5</p>
        <p>6</p>
        
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
    function searchNow(url, keyword){
        $.ajax({
            url,
            type: 'GET',
            data: {
                keyword: keyword
            },
            success: function(response){
                console.log(response);
            },
            error: function(error){
                console.log(error);
            }
        })
    }
    $(document).ready(()=>{
        let debounceTimer;
        const txtName = "#{{$modalId}}_txtName"
        const url = "{{route($tableName.'.searchable')}}";
        // console.log(tableName);
        $(txtName).on('keyup', function(){
            clearTimeout(debounceTimer); // Clear the previous timer

            debounceTimer = setTimeout(function() {
                let inputValue = $(txtName).val();
                searchNow(url, inputValue);
            }, 500)
        })
    })   
</script>
@endsection
