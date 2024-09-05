@extends("modals.modal-medium")
@section($modalId.'-header', "Select ".strtoupper($tableName))
@section($modalId.'-body')
    <div class="m-1 p-1">
        <div class="font-bold">Keywords:</div>
        <input id="{{$modalId}}_txtName" type="text" name="name" class="w-full rounded p-1 border-gray-200"/>
        <div class="font-bold">Search Results:</div>
        <div id="{{$modalId}}_result" class="overflow-y-scroll border1 rounded bg-gray-50 p-1 border" style="height: 400px;"></div>
        <div class="font-bold">Selected Value:</div>
        <input id="{{$modalId}}_selectedValue" type="hidden" />
        <div id="{{$modalId}}_selectedText" class="rounded border w-full bg-gray-100 p-1"></div>
    </div>
@endsection

@section($modalId.'-footer')
<div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
    <x-renderer.button click="closeModal('{{$modalId}}')">Cancel</x-renderer.button>
    <x-renderer.button click="closeModal('{{$modalId}}')" id="{{$modalId}}_btnSelect" htmlType="button" class="mx-1" type='success'>Select</x-renderer.button>
</div>
@endsection

@section($modalId.'-javascript')
<script>    
    $(document).ready(()=>{
        let debounceTimer;
        const modalId = "{{$modalId}}"
        const multiple = "{{$multipleStr}}"
        const inputName = "{{$inputName}}"
        const divValueName = "{{$divValueName}}"
        const divTextName = "{{$divTextName}}"
        const txtName = '#'+ modalId + "_txtName"
        const url = "{{route($tableName.'.searchable')}}";

        $(txtName).focus();
        $(txtName).on('keyup', function(){
            clearTimeout(debounceTimer); // Clear the previous timer
            
            debounceTimer = setTimeout(function() {
                const inputValue = $(txtName).val();
                const selectingValues = $('#'+modalId+'_selectedValue').val();
                // if(inputValue.length < 3) return;
                // console.log(inputValue);

                modalSearchableDialogInvoke(url, inputValue, multiple, selectingValues, modalId);
            }, 500)
        })

        const btnSelect = '#'+ modalId + "_btnSelect"
        $(btnSelect).on('click',function(){
            // $('#'+modalId).hide()
            let selectedValues = $('#'+modalId+'_selectedValue').val();
            selectedValues = selectedValues ? selectedValues.split(",") : [];
            console.log(divValueName, selectedValues);

            const inputs = []
            selectedValues.forEach(id => inputs.push(renderInputField(inputName, id)));
            getEById(divValueName).html(inputs.join(''));

            const selectedTexts = $('#'+modalId+'_selectedText').html();
            // console.log(divTextName, selectedTexts);
            getEById(divTextName).html(selectedTexts);

        })
        
        const onLoaded = () =>{
            const allInputs = $(`[name='${inputName}']`);
            const selectedIds = []
            allInputs.each(input=>(selectedIds.push( allInputs[input].value)));
            const selectedValues = selectedIds.join();
            $('#'+modalId+'_selectedValue').val(selectedValues);
            
            const selectedTexts = getEById(divTextName).html();
            $('#'+modalId+'_selectedText').html(selectedTexts || '&nbsp;');

            modalSearchableDialogInvoke(url, null, multiple, selectedValues, modalId);            
        }
        onLoaded();
    })   
</script>
@endsection
