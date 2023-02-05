<x-renderer.table 
    :columns="$columns" 
    :dataSource="$dataSource" 
    showNo="{{true}}" 
    {{-- type="{{$lineType}}" --}}
    footer="{{$fn === '' ? '(Default column settings loaded)' : ''}}"
/>
