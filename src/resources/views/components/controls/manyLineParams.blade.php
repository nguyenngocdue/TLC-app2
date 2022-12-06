<x-renderer.table 
    :columns="$columns" 
    :dataSource="$dataSource" 
    showNo="{{true}}" 
    footer="{{$fn ? '' : '(Default column settings loaded)'}}"
/>
