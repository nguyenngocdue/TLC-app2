<x-renderer.table 
    :columns="$columns" 
    :dataSource="$dataSource" 
    showNo="{{true}}" 
    {{-- type="{{$lineType}}" --}}
    footer="{{$fn === '' ? '(Default column settings loaded)' : ''}}"
/>

<x-renderer.table 
    :columns="$editableColumns" 
    :dataSource="$dataSource" 
    showNo="{{true}}" 
    {{-- type="{{$lineType}}" --}}
    footer="{{$fn === '' ? '(Default column settings loaded)' : ''}}"
/>
<input name="tableNames[table01]" value="{{$tableName}}" type="text" />

