<x-renderer.table 
    tableName="{{$table01ROName}}"
    :columns="$readOnlyColumns" 
    :dataSource="$dataSource" 
    showNo="{{true}}" 
    {{-- type="{{$lineType}}" --}}
    footer="{{$fn === '' ? '(Default column settings loaded)' : ''}}"
    />

<x-renderer.table 
    tableName="{{$table01Name}}"
    :columns="$editableColumns" 
    :dataSource="$dataSource" 
    showNo="{{true}}" 
    {{-- type="{{$lineType}}" --}}
    footer="{{$fn === '' ? '(Default column settings loaded)' : ''}}"
/>
<script>
    editableColumns['{{$table01Name}}']=@json($editableColumns);
    </script>

<x-renderer.button type="success" title="Add a new line" onClick="addANewLine({tableId:'{{$table01Name}}', columns: editableColumns['{{$table01Name}}']})">Add A New Item</x-renderer.button>
<input name="tableNames[{{$table01Name}}]" value="{{$tableName}}" type="hidden" />

