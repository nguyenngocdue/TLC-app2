<x-renderer.table 
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
<input name="tableNames[{{$table01Name}}]" value="{{$tableName}}" type="hidd1en" />

