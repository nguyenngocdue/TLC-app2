{{-- @extends('layouts.app')
@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('content') --}}

<div class="px-4">
    {{-- @include('components.reports.shared-parameter') --}}
    @if(!empty($legendColors))
    <x-renderer.card class="mb-5">
        <x-reports.color-legend-report :dataSource="$legendColors" />
    </x-renderer.card>
    @endif
    @php
        $tl = "<div></div>";
        $tc = "<x-reports.utility-report routeName='$routeName'/>"; 
        $tr = "<x-reports.per-page-report typeReport='$typeReport' entity='$entity' routeName='$routeName' page-limit='$pageLimit' formName='updatePerPage' />"; 
    @endphp
    <x-renderer.report.pivot-table 
                    showNo={{true}} 
                    modeType="{{$modeType}}"  
                    :tableDataHeader="$tableDataHeader" 
                    :tableColumns="$tableColumns" 
                    :params="$params" 
                    :dataSource="$tableDataSource"  
                    tableTrueWidth={{$tableTrueWidth?1:0}} 
                    page-limit="{{$pageLimit}}" 
                    headerTop=10
                    maxH="{{$maxH}}" 
                    
                    showPaginationTop="true"
                    topLeftControl="{!!$tl!!}" 
                    topCenterControl="{!!$tc!!}" 
                    topRightControl="{!!$tr!!}" 
                    />
</div>
{{-- @endsection --}}
