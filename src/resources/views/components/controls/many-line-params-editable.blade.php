<x-renderer.table tableName="{{$table01Name}}" :columns="$editableColumns" :dataHeader="$dataSource2ndThead" :dataSource="$dataSourceWithOld" showNo="{{$showNo?1:0}}" showNoR="{{$showNoR?1:0}}" footer="{{$tableFooter}}" maxH={{false}} tableDebug={{$tableDebug}} editable="{{$editable?1:0}}"/>
<script>
    editableColumns['{{$table01Name}}'] = @json($editableColumns);
    dateTimeControls['{{$table01Name}}'] = @json($dateTimeColumns);
    tableObject['{{$table01Name}}'] = {
        // tableId:'{{$table01Name}}', 
        columns: editableColumns['{{$table01Name}}']
        , showNo: {{$showNo ? 1 : 0}}
        , showNoR: {{$showNoR ? 1 : 0}}
        , tableDebugJs: {{$tableDebug ? 1 : 0}}
        , isOrderable: {{$isOrderable ? 1 : 0}}
        , tableName: "{{$tableName}}"
        , dateTimeControls: dateTimeControls['{{$table01Name}}']
    };
    tableObjectColName["{{$colName}}"] = {
        name:'{{$table01Name}}'
    };

</script>

<div class="flex justify-between">
    <div>
        <x-renderer.button disabled="{{$readOnly}}" id="btnAddANewLine_{{$table01Name}}" type="success" title="Add a new line" onClick="addANewLine({tableId: '{{$table01Name}}'})">Add A New Item</x-renderer.button>
        @if(isset($tableSettings['showBtnAddFromAList']) && $tableSettings['showBtnAddFromAList'])
            <x-renderer.button disabled="{{$readOnly}}" id="btnAddFromAList_{{$table01Name}}" click="toggleModal('{{$table01Name}}')" keydown="closeModal('{{$table01Name}}')" type="success" title="Add from a list">Add From A List</x-renderer.button>
            <x-modals.modal-add-from-a-list modalId='{{$table01Name}}' />
        @endif
    </div>

    @if( isset($tableSettings['showBtnRecalculate']) && $tableSettings['showBtnRecalculate'])
        <x-renderer.button disabled="{{$readOnly}}" type="secondary" onClick="refreshCalculation('{{$table01Name}}')"><i class="fa-solid fa-calculator"></i> Recalculate</x-renderer.button>
        <script>
            $(document).ready(()=>{
                // console.log("Recalculate")
                refreshCalculation('{{$table01Name}}')
            })
        </script>
    @endif
</div>

<i id="iconSpin_{{$table01Name}}" class="fa-duotone fa-spinner fa-spin text-green-500" style="display: none"></i>
<input class="bg-gray-200" readonly name="tableNames[{{$table01Name}}]" value="{{$tableName}}" type="{{$tableDebugTextHidden}}" />
@if($tableName == 'hr_overtime_request_lines')
<br />
<br />
<x-renderer.card title="Remaining Hours Legend">
    <div class="grid lg:grid-cols-5 md:grid-cols-5 grid-cols-2">
        <div class="flex">
            <div class="border h-6 w-6 mr-2 bg-red-600"></div>< 0%
        </div>
        <div class="flex">
            <div class="border h-6 w-6 mr-2 bg-pink-400"></div> 0% to < 25%
        </div>
        <div class="flex">
            <div class="border h-6 w-6 mr-2 bg-orange-300"></div> 25% to < 50%
        </div>
        <div class="flex">
            <div class="border h-6 w-6 mr-2 bg-yellow-300"></div> 50% to < 75%
        </div>
        <div class="flex">
            <div class="border h-6 w-6 mr-2 bg-green-300"></div> >= 75%
        </div>
    </div>
</x-renderer.card>
@endif
{{-- This is for when clicked "Add a new item", if the column is parent_id and parent_type and might be invisible, --}}
{{-- its value will get from here --}}
@once
    <input class="bg-gray-200" readonly title="entityParentType" id="entityParentType" value="{{$entityType}}" type="{{$tableDebugTextHidden}}" />
    <input class="bg-gray-200" readonly title="entityParentId" id="entityParentId" value="{{$entityId}}" type="{{$tableDebugTextHidden}}" />
    <input class="bg-gray-200" readonly title="currentUserId" id="currentUserId" value="{{$userId}}" type="{{$tableDebugTextHidden}}" />
    <input class="bg-gray-200" readonly title="entityProjectId" id="entityProjectId" value="{{$entityProjectId}}" type="{{$tableDebugTextHidden}}" />
    <input class="bg-gray-200" readonly title="entitySubProjectId" id="entitySubProjectId" value="{{$entitySubProjectId}}" type="{{$tableDebugTextHidden}}" />
@endonce
