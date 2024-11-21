{{-- @dd($tableDataHeader, $tableColumns) --}}
<x-renderer.table 
    maxH="{{$maxH}}"  
    showNo={{true}} 
    :dataHeader="$tableDataHeader" 
    :columns="$tableColumns" 
    :dataSource="$tableDataSource" 
    tableTrueWidth={{$tableTrueWidth?1:0}} 
    headerTop={{10 * 16}}
    
    showPaginationTop="{{$showPaginationTop}}"
    topLeftControl="{!!$topLeftControl!!}" 
    topCenterControl="{!!$topCenterControl!!}" 
    topRightControl="{!!$topRightControl!!}" 
     />
