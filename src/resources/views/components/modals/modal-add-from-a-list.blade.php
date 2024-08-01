@extends("modals.modal-large")

@section($modalId.'-header', "Select Items from a List")

@php
    $groupIdName = $table01Name . '_modal_group_id_' . $groupDataSourceName;
    $groupTableName = $table01Name . '_modal_group_' . $groupDataSourceName;

    $fieldIdName = $table01Name . '_modal_items_id_' . $itemDataSourceName;
    $fieldTableName = $table01Name . '_modal_items_' . $itemDataSourceName;
@endphp

@section($modalId.'-body')
    <div class="h-4"></div>
    @if($groupDataSourceName)    
    <x-modals.parent-type7-generic 
        name='{{$groupIdName}}' 
        tableName="{{$groupTableName}}" 
        dataSourceTableName="{{$groupDataSourceName}}"
        />
    @endif
    
    <div class="py-2">
        <x-renderer.button onClick="radioOrCheckboxSelectAll('{{$fieldIdName}}')">Select All</x-renderer.button>
        <x-renderer.button onClick="radioOrCheckboxDeselectAll('{{$fieldIdName}}')">Deselect All</x-renderer.button>
        {{-- <x-renderer.button onClick="radioOrCheckboxChangeOrder('ot_user_id', 'ot_users', '{{$groupIdName}}')">Sort by StaffID Order</x-renderer.button> --}}
    </div>
    <x-modals.parent-id7-generic 
        name='{{$fieldIdName}}' 
        tableName="{{$fieldTableName}}" 
        groupIdName="{{$groupIdName}}"
        dataSourceTableName="{{$itemDataSourceName}}"
        eloquentFunctionName="{{$eloquentFunctionName}}"
        multiple=1
        control='radio-or-checkbox2a' 
        {{-- span=6 --}}
        />
@endsection

@section($modalId.'-footer')
<div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
    <x-renderer.button 
        click="closeModal('{{$modalId}}')"
        >Cancel</x-renderer.button>
    <x-renderer.button 
        class="mx-2" 
        type='success' 
        click="loadListToTable(addLinesToTableFormModelList, '{{$fieldIdName}}', '{{$table01Name}}', '{{$xxxForeignKey}}', '{{$modalId}}')"
        >Populate</x-renderer.button>
</div>
@endsection

@section($modalId.'-javascript')
<script>
const addLinesToTableFormModelList = (listId, tableId, xxxForeignKey) => {
    const x = $("input[name='" + listId + "[]']")
    const result = []
    for (let i = 0; i < x.length; i++) {
        if (x[i].checked) result.push(x[i].value)
    }
    // console.log(result)
    for (let i = 0; i < result.length; i++) {        
        // const today = moment().format('DD/MM/YYYY')
        const valuesOfOrigin = { [xxxForeignKey]: result[i]}
        // console.log("Add line", tableId, valuesOfOrigin)
        addANewLine({ tableId, valuesOfOrigin, isDuplicatedOrAddFromList:true, batchLength: result.length })
    }
}
</script>
@endsection