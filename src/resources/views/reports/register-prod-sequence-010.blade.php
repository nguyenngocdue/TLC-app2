@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$mode)
@section('content')
@php
    $projectId = is_array($params['project_id']) ? join(",", $params['project_id']) : $params['project_id'];
    $subProjectId = is_array($params['sub_project_id']) ? join(",", $params['sub_project_id']) : $params['sub_project_id'];
    $prodRoutingId = is_array($params['prod_routing_id']) ? join(",", $params['prod_routing_id']) : $params['prod_routing_id'];

    $params['prod_discipline_id'] = $params['prod_discipline_id'] ?? [];
    $prodDisciplineId = is_array($params['prod_discipline_id']) ? join(",", $params['prod_discipline_id']) : $params['prod_discipline_id'];
    $sequenceModeId = $params['sequence_mode'];
    $dateToCompare = isset($params['picker_date']) ? App\Utils\Support\DateReport::basicFormatDateString(str_replace('/', '-', $params['picker_date']), 'Y-m-d'): null;
    
@endphp

<div class="px-4 ">
    <div class="justify-end pb-5"></div>
    <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        {{-- @dd($type) --}}
        @include('components.reports.shared-parameter')
    </div>
   
    @php
        $tl = "<div></div>";
        $tc = "<x-reports.utility-report routeName='$routeName'/>"; 
        $tr = "<x-reports.per-page-report typeReport='$typeReport' entity='$entity' routeName='$routeName' page-limit='$pageLimit' formName='updatePerPage' />"; 
    @endphp
        <x-renderer.matrix-for-report.prod_sequences 
                    subProjectId="{{$subProjectId}}" 
                    prodRoutingId="{{$prodRoutingId}}"
                    sequenceModeId="{{$sequenceModeId}}"
                    prodDisciplineId="{{$prodDisciplineId}}"
                    dateToCompare="{{$dateToCompare}}"/>
</div>
@endsection
