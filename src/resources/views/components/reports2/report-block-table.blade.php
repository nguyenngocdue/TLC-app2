
<x-renderer.table 
    headerTop=12 
    showNo=true 
    :columns="$tableColumns" :dataSource="$tableDataSource" :dataHeader="$secondHeaderCols"
    tableTrueWidth="{{ $tableTrueWidth }}" maxH="{{ $maxHeight }}" rotate45width="{{ $rotate45Width }}"
    showPaginationTop="{{$hasPagination}}"
    page-limit="10"
    rotate45Height="{{ $rotate45Height }}" 
    topLeftControl="{!! $topLeftControl !!}" topCenterControl="{!! $topCenterControl !!}"
    topRightControl="{!! $topRightControl !!}" bottomLeftControl="{!! $bottomLeftControl !!}"
    bottomCenterControl="{!! $bottomCenterControl !!}" bottomRightControl="{!! $bottomRightControl !!}" 
    />
