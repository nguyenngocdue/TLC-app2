@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', Str::ucfirst($typeReport))
@section('content')

{{-- @dd($modeParams, $tableDataSource, $tableColumns); --}}
<div class="px-4">
    <div class="flex justify-end pb-5 pr-4 ">
        <x-reports.per-page-report typeReport="{{$typeReport}}" entity="{{$entity}}" routeName="{{$routeName}}" page-limit="{{$pageLimit}}" />
    </div>
    <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <div class="pb-2">
            <x-reports.mode-report :dataSource="$modeOptions" :column="$modeColumns" formName="mode_options" :itemsSelected="['mode_option' =>$currentMode]" userId="{{$currentUserId}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
        </div>
        <x-reports.parameter-report :dataSource="$dataModeControl" :itemsSelected="$modeParams" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    </div>
    <x-renderer.table showNo={{true}} :columns="$tableColumns" :dataSource="$tableDataSource" rotate45Width={{$rotate45Width}} />
    @endsection
</div>
