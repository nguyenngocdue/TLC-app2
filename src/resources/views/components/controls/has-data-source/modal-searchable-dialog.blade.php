@extends("modals.modal-medium")
@section($modalId.'-header', "Select ".strtoupper($tableName))
@section($modalId.'-body')
    <div class="m-1 p-1">
        <div class="font-bold">Keywords: (minimum 3 charactors)</div>
        <input id="{{$modalId}}_txtName" type="text" name="name" class="w-full rounded p-1 border-gray-200"/>
        <div class="font-bold">Search Results:</div>
        <div id="{{$modalId}}_result" class="overflow-y-scroll border1 rounded bg-gray-50 p-1" style="height: 500px;">

        </div>
        
    </div>
@endsection

@section($modalId.'-footer')
<div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
    <x-renderer.button click="closeModal('{{$modalId}}')">Cancel</x-renderer.button>
    <x-renderer.button onClick="submitModal('{{$modalId}}')" htmlType="button" class="mx-1" type='success'>Select</x-renderer.button>
</div>
@endsection

@section($modalId.'-javascript')
<script src="{{asset('js/components/ModalSearchableDialog.js')}}"></script>
<script>    
    $(document).ready(()=>{
        let debounceTimer;
        const modalId = "{{$modalId}}"
        const multiple = "{{$multipleStr}}"
        const txtName = '#'+ modalId + "_txtName"
        const url = "{{route($tableName.'.searchable')}}";
        // console.log(tableName);
        $(txtName).focus();
        $(txtName).on('keyup', function(){
            clearTimeout(debounceTimer); // Clear the previous timer

            debounceTimer = setTimeout(function() {
                let inputValue = $(txtName).val();
                if(inputValue.length < 3) return;
                console.log(inputValue);
                modalSearchableDialogInvoke(url, inputValue, multiple, modalId);
            }, 500)
        })
    })   
</script>
@endsection
