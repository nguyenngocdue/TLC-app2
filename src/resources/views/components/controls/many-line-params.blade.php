{{-- @if($tableName !== 'hse_corrective_actions')  --}}

<x-renderer.table 
    tableName="{{$table01ROName}}"
    :columns="$readOnlyColumns" 
    :dataSource="$dataSource" 
    showNo="{{true}}" 
    footer="{{$tableFooter}}"
    />

{{-- @else --}}

<x-renderer.table 
    tableName="{{$table01Name}}"
    :columns="$editableColumns" 
    :dataSource="$dataSource" 
    showNo="{{true}}" 
    showNoR="{{true}}" 
    footer="{{$tableFooter}}"
/>
<script>
    editableColumns['{{$table01Name}}'] = @json($editableColumns);
    tableObject['{{$table01Name}}'] = {
        tableId:'{{$table01Name}}', 
        columns: editableColumns['{{$table01Name}}'],
        showNo:true,
        showNoR:true,
        tableDebug: {{$tableDebug}},
    }
    </script>
<br/>
<x-renderer.button type="success" title="Add a new line" onClick="addANewLine({tableId: '{{$table01Name}}'})">Add A New Item</x-renderer.button>
<input class="bg-gray-200" readonly name="tableNames[{{$table01Name}}]" value="{{$tableName}}" type="{{$tableDebugTextHidden}}" />
<input class="bg-gray-200" readonly id="entityParentId" value="{{$entityId}}" type="{{$tableDebugTextHidden}}" />

{{-- @endif --}}