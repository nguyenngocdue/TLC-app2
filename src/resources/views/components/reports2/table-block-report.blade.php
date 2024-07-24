<x-renderer.heading level=5 title='Block Id: {{ $block->id }}' xalign='left'>
    <a href="{{ route('rp_blocks.edit', $block->id) }}" target="blank">
        {{ $name }}
    </a>
</x-renderer.heading>
<x-renderer.heading level=6 xalign='left'>{{ $description }}</x-renderer.heading>

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
@php
    //dump($tableColumns);
@endphp
<x-renderer.card title="">
    <x-renderer.table headerTop=12 showNo=true :columns="$tableColumns" :dataSource="$tableDataSource" :dataHeader="$dataHeader"
        tableTrueWidth="{{ $tableTrueWidth }}" maxH="{{ $maxHeight }}" rotate45width="{{ $rotate45Width }}"
        rotate45Height="{{ $rotate45Height }}" showPaginationTop="{{ true }}"
        topLeftControl="{!! $topLeftControl !!}" topCenterControl="{!! $topCenterControl !!}"
        topRightControl="{!! $topRightControl !!}" bottomLeftControl="{!! $bottomLeftControl !!}"
        bottomCenterControl="{!! $bottomCenterControl !!}" bottomRightControl="{!! $bottomRightControl !!}" />
</x-renderer.card>
