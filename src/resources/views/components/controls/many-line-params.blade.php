@php $showNo = true; $showNoR = false; @endphp

@if(!$editable)  
    <x-renderer.table 
        tableName="{{$table01ROName}}"
        :columns="$readOnlyColumns" 
        :dataSource="$dataSource" 
        showNo="{{$showNo?1:0}}" 
        footer="{{$tableFooter}}"
        />
@else
    <x-renderer.table 
        tableName="{{$table01Name}}"
        :columns="$editableColumns" 
        :dataHeader="$dataSource2ndThead"
        :dataSource="$dataSourceWithOld" 
        showNo="{{$showNo?1:0}}"
        showNoR="{{$showNoR?1:0}}" 
        footer="{{$tableFooter}}"
        maxH={{false}}
        tableDebug={{$tableDebug}}
    />
    <script>
        editableColumns['{{$table01Name}}'] = @json($editableColumns);
        tableObject['{{$table01Name}}'] = {
            // tableId:'{{$table01Name}}', 
            columns: editableColumns['{{$table01Name}}'],
            showNo: {{$showNo?1:0}},
            showNoR: {{$showNoR?1:0}},
            tableDebugJs: {{$tableDebug?1:0}},
            isOrderable: {{$isOrderable?1:0}},
            tableName: "{{$tableName}}",
        }
        </script>
    <x-renderer.button type="success" title="Add a new line" onClick="addANewLine({tableId: '{{$table01Name}}'})">Add A New Item</x-renderer.button>
    <input class="bg-gray-200" readonly name="tableNames[{{$table01Name}}]" value="{{$tableName}}" type="{{$tableDebugTextHidden}}" />
    {{-- This is for when clicked "Add a new item", if the column is parent_id and parent_type and might be invisible, --}}
    {{-- its value will get from here --}}
    <input class="bg-gray-200" readonly title="entityParentType" id="entityParentType" value="{{$entityType}}" type="{{$tableDebugTextHidden}}" />
    <input class="bg-gray-200" readonly title="entityParentId" id="entityParentId" value="{{$entityId}}" type="{{$tableDebugTextHidden}}" />
    <input class="bg-gray-200" readonly title="userId" id="userId" value="{{$userId}}" type="{{$tableDebugTextHidden}}" />

@endif