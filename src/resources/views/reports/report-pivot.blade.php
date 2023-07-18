@extends('layouts.app')

@section('topTitle', "")
@section('title', "")
@section('tooltip', "")
@section('content')

{{-- @dd($paramColumns) --}}
<div class="px-4">
    <div class="justify-end pb-5"></div>
    <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
        <x-reports.parameter3-report :itemsSelected="$modeParams" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    </div>
    <div class="flex pb-2 pr-4 ">
        <x-reports.utility-report routeName="{{$routeName}}" />
        <x-reports.per-page-report typeReport="{{$typeReport}}" entity="{{$entity}}" routeName="{{$routeName}}" page-limit="{{$pageLimit}}" formName="updatePerPage" />
    </div>
    <x-renderer.report.pivot-table modeType="{{$modeType}}" :dataSource="$dataSource" :itemsSelected="$modeParams" />
</div>
    @endsection








