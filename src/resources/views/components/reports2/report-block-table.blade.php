{{-- @php
    $rotate45Width = 300;
    $rotate45Height = 250;
    $tableTrueWidth = 1;
    $lineIgnoreNo=1
@endphp --}}
<x-renderer.table 
    headerTop=12 
    showNo=true 
    :columns="$tableColumns" :dataSource="$tableDataSource" :dataHeader="$secondHeaderCols"
    tableTrueWidth="{{ $tableTrueWidth }}" maxH="{{ $maxHeight }}"
    showPaginationTop="{{$hasPagination}}"
    showPaginationBottom={{ true }}
    page-limit="10"
    :rotate45Width="$rotate45Width" 
    :rotate45Height="$rotate45Height"
    topLeftControl="{!! $topLeftControl !!}" topCenterControl="{!! $topCenterControl !!}"
    topRightControl="{!! $topRightControl !!}" bottomLeftControl="{!! $bottomLeftControl !!}"
    bottomCenterControl="{!! $bottomCenterControl !!}" bottomRightControl="{!! $bottomRightControl !!}" 
    lineIgnoreNo=1
    />

@if($legendEntityType)
    <x-renderer.legend type="{{$legendEntityType}}" title="Legend of Status" />
    <br/>
@endif