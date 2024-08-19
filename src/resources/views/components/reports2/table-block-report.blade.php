<x-renderer.report2.title-description-block :block="$block" />

{{-- @dump($rotate45Width, $rotate45Height) --}}

{{-- @php
        $tl = "<div></div>";
        $tc = "<x-reports.utility-report routeName='$routeName'/>"; 
        $tr = "<x-reports.per-page-report typeReport='$typeReport' entity='$entity' routeName='$routeName' page-limit='$pageLimit' formName='updatePerPage' />"; 
    @endphp --}}
{{-- @switch($topLeftControl || $topCenterControl || $topRightControl || $bottomLeftControl || $bottomCenterControl || $bottomRightControl)  
        @case(621)
            $topLeftControl = "<x-reports.utility-report routeName='$routeName'/>"; 
        @break
    @endswitch  --}}
{{-- @php
    dd($tableDataSource);
@endphp --}}
<x-renderer.card title="">
    <x-renderer.table headerTop=12 showNo=true :columns="$tableColumns" :dataSource="$tableDataSource" :dataHeader="$dataHeader"
        tableTrueWidth="{{ $tableTrueWidth }}" maxH="{{ $maxHeight }}" rotate45width="{{ $rotate45Width }}"
        rotate45Height="{{ $rotate45Height }}" showPaginationTop="{{ true }}"
        topLeftControl="{!! $topLeftControl !!}" topCenterControl="{!! $topCenterControl !!}"
        topRightControl="{!! $topRightControl !!}" bottomLeftControl="{!! $bottomLeftControl !!}"
        bottomCenterControl="{!! $bottomCenterControl !!}" bottomRightControl="{!! $bottomRightControl !!}" />
</x-renderer.card>
