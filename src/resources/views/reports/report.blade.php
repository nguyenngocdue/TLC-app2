@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', Str::ucfirst($typeReport))
@section('content')

{{-- @dd($modeParams, $tableDataSource, $tableColumns); --}}
<div class="px-4">
    <div class="justify-end pb-5"></div>
    <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
        <x-reports.mode-report :dataSource="$modeOptions" :column="$modeColumns" formName="mode_options" :itemsSelected="['mode_option' =>$currentMode]" userId="{{$currentUserId}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
        <x-reports.parameter-report :dataSource="$dataModeControl" :itemsSelected="$modeParams" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    </div>
    <div class="flex pb-2 pr-4 ">
        <x-reports.utility-report routeName="{{$routeName}}" />
        <x-reports.per-page-report typeReport="{{$typeReport}}" entity="{{$entity}}" routeName="{{$routeName}}" page-limit="{{$pageLimit}}" formName="updatePerPage" />
    </div>

    @php
    // $tc = "<x-renderer.tag>Top Centerdfgdfgfdgfdgfdgfdg</x-renderer.tag> ";
    $tc = "
    <x-reports.utility-report routeName='$routeName' ";
    @endphp
    {{-- <x-renderer.tag>Top Center</x-renderer.tag> --}}
    <x-renderer.table topCenterControl=" {!!$tc!!}" showNo={{true}} :columns="$tableColumns" :dataSource="$tableDataSource" rotate45Width={{$rotate45Width}} />
    @endsection
</div>
