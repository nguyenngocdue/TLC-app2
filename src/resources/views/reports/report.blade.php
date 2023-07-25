@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $modeReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$mode)
@section('content')

{{-- @dd($modeParams, $tableDataSource, $tableColumns); --}}
<div class="px-4">
    <div class="justify-end pb-5"></div>
    <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
        <x-reports.parameter3-report :itemsSelected="$modeParams" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    </div>
    @if(!empty($legendColors))
    <x-renderer.card class="mb-5">
        <x-reports.color-legend-report :dataSource="$legendColors" />
    </x-renderer.card>
    @endif
    <div class="flex pb-2 pr-4 ">
        <x-reports.utility-report routeName="{{$routeName}}" />
        <x-reports.per-page-report typeReport="{{$typeReport}}" entity="{{$entity}}" routeName="{{$routeName}}" page-limit="{{$pageLimit}}" formName="updatePerPage" />
    </div>

    @php
    $tc = "
    <x-reports.utility-report routeName='$routeName' ";
    @endphp
    {{-- <x-renderer.tag>Top Center</x-renderer.tag> --}}
    <x-renderer.table 
        topCenterControl=" {!!$tc!!}" showNo={{true}} :dataHeader="$tableDataHeader" :columns="$tableColumns" :dataSource="$tableDataSource" rotate45Width={{$rotate45Width}} maxH="{{$maxH}}" tableTrueWidth={{$tableTrueWidth?1:0}} />
    @endsection
</div>
