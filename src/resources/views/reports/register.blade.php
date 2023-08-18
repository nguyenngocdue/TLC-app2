@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$mode)
@section('content')

<div class="px-4 ">
    <div class="justify-end pb-5"></div>
    <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <x-reports.parameter3-report :itemsSelected="$modeParams" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    </div>
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
    @if($typeOfView === 'report-pivot')
    <x-renderer.report.pivot-table modeType="{{$modeType}}"  showNo={{true}} :dataHeader="$tableDataHeader" :columns="$tableColumns" :modeParams="$modeParams" :dataSource="$tableDataSource"  tableTrueWidth={{$tableTrueWidth?1:0}} page-limit="{{$pageLimit}}" headerTop=10 />
    @else
    <x-renderer.table 
                    showNo={{true}} 
                    :dataHeader="$tableDataHeader" 
                    :columns="$tableColumns" 
                    :dataSource="$tableDataSource" 
                    rotate45Width={{$rotate45Width}} 
                    maxH="{{$maxH}}" 
                    tableTrueWidth={{$tableTrueWidth?1:0}}

                    showPaginationTop="true"
                    topLeftControl="{!!$tl!!}" 
                    topCenterControl="{!!$tc!!}" 
                    topRightControl="{!!$tr!!}" 
                    />
    @endif
</div>
@endsection
