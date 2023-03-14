@php $showNo = true; $showNoR = false; @endphp

@if(!$editable)
<x-renderer.table tableName="{{$table01ROName}}" :columns="$readOnlyColumns" :dataSource="$dataSource" showNo="{{$showNo?1:0}}" footer="{{$tableFooter}}" noCss="{{$noCss}}" />
@else
<x-renderer.table tableName="{{$table01Name}}" :columns="$editableColumns" :dataHeader="$dataSource2ndThead" :dataSource="$dataSourceWithOld" showNo="{{$showNo?1:0}}" showNoR="{{$showNoR?1:0}}" footer="{{$tableFooter}}" maxH={{false}} tableDebug={{$tableDebug}} />
<script>
    editableColumns['{{$table01Name}}'] = @json($editableColumns);
    tableObject['{{$table01Name}}'] = {
        // tableId:'{{$table01Name}}', 
        columns: editableColumns['{{$table01Name}}']
        , showNo: {{$showNo ? 1 : 0}}
        , showNoR: {{$showNoR ? 1 : 0}}
        , tableDebugJs: {{$tableDebug ? 1 : 0}}
        , isOrderable: {{$isOrderable ? 1 : 0}}
        , tableName: "{{$tableName}}"
    , }

</script>
<x-renderer.button id="btnAddANewLine_{{$table01Name}}" type="success" title="Add a new line" onClick="addANewLine({tableId: '{{$table01Name}}'})">Add A New Item</x-renderer.button>
{{-- @dump($tableSettings) --}}
@isset($tableSettings['showBtnAddFromAList'])
@if($tableSettings['showBtnAddFromAList'])

<x-renderer.button click="toggleListingTable('{{$table01Name}}')" keydown="closeListingTable('{{$table01Name}}')" id="btnAddFromAList_{{$table01Name}}" type="success" title="Add from a list">Add From A List</x-renderer.button>
<x-modals.modal-add-from-a-list modalId='{{$table01Name}}' />
@endif
@endisset

<i id="iconSpin_{{$table01Name}}" class="fa-duotone fa-spinner fa-spin text-green-500" style="display: none"></i>
<input class="bg-gray-200" readonly name="tableNames[{{$table01Name}}]" value="{{$tableName}}" type="{{$tableDebugTextHidden}}" />
<br />
<br />
@if($tableName == 'hr_overtime_request_lines')
<x-renderer.card title="Remaining Hours Legend">
    <div class="grid lg:grid-cols-5 md:grid-cols-5 grid-cols-2">
        <div class="flex">
            <div class="border h-6 w-6 mr-2 bg-red-600"></div>
            < 0 hours/month</div>
                <div class="flex">
                    <div class="border h-6 w-6 mr-2 bg-pink-400"></div> 0 to < 10 hours/month</div>
                        <div class="flex">
                            <div class="border h-6 w-6 mr-2 bg-orange-300"></div> 10 to < 20 hours/month</div>
                                <div class="flex">
                                    <div class="border h-6 w-6 mr-2 bg-yellow-300"></div> 20 to < 30 hours/month</div>
                                        <div class="flex">
                                            <div class="border h-6 w-6 mr-2 bg-green-300"></div> above 30 hours/month
                                        </div>
                                </div>
</x-renderer.card>
@endif
{{-- This is for when clicked "Add a new item", if the column is parent_id and parent_type and might be invisible, --}}
{{-- its value will get from here --}}
<input class="bg-gray-200" readonly title="entityParentType" id="entityParentType" value="{{$entityType}}" type="{{$tableDebugTextHidden}}" />
<input class="bg-gray-200" readonly title="entityParentId" id="entityParentId" value="{{$entityId}}" type="{{$tableDebugTextHidden}}" />
<input class="bg-gray-200" readonly title="userId" id="userId" value="{{$userId}}" type="{{$tableDebugTextHidden}}" />

@endif
