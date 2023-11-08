@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$mode)
@section('content')
@php
    $projectId = $params['project_id'];
    $subProjectId = $params['sub_project_id'];
    $prodRoutingId = $params['prod_routing_id'];
@endphp

<div class="px-4 ">
    <div class="justify-end pb-5"></div>
    <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <x-reports.parameter3-report :itemsSelected="$params" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    </div>
   
    @php
        $tl = "<div></div>";
        $tc = "<x-reports.utility-report routeName='$routeName'/>"; 
        $tr = "<x-reports.per-page-report typeReport='$typeReport' entity='$entity' routeName='$routeName' page-limit='$pageLimit' formName='updatePerPage' />"; 
    @endphp
        <x-renderer.matrix-for-report.qaqc_wirs subProjectId="{{$subProjectId}}" prodRoutingId="{{$prodRoutingId}}" />
         @if(!empty($legendColors))
            <x-reports.color-legend-report :dataSource="$legendColors" />
        @endif
</div>
@endsection
