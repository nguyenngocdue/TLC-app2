{{-- @dd($tableDataSource[98]) --}}
<x-renderer.table 
    headerTop={{$headerTop}}
    showNo={{true}} 
    :columns="$tableColumns" :dataSource="$tableDataSource" :dataHeader="$secondHeaderCols"
    tableTrueWidth="{{ $tableTrueWidth }}" maxH="{{ $maxHeight }}"
    showPaginationTop="{{true }}"
    showPaginationBottom={{ true }}
    page-limit="10"
    :rotate45Width="$rotate45Width" 
    :rotate45Height="$rotate45Height"
    topLeftControl="{!! $topLeftControl !!}" topCenterControl="{!! $topCenterControl !!}"
    topRightControl="{!! $topRightControl !!}" bottomLeftControl="{!! $bottomLeftControl !!}"
    bottomCenterControl="{!! $bottomCenterControl !!}" bottomRightControl="{!! $bottomRightControl !!}" 
    />

@if($legendEntityType)
    <x-renderer.legend type="{{$legendEntityType}}" title="Legend of Status" />
    <br/>
@endif