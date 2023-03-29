@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $typeReport)
@section('content')
<div class="px-4 ">
    <div class="flex justify-end pb-2 pr-4 ">
        <x-reports.per-page-report typeReport="{{$typeReport}}" entity="{{$entity}}" routeName="{{$routeName}}" page-limit="{{$pageLimit}}" />
    </div>

    <div class="flex justify-end  w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <x-reports.mode-report :dataSource="$modeOptions" :column="$modeColumns" formName="mode_options" :itemsSelected="['mode_option' =>$currentMode]" userId="{{$currentUserId}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
        <x-reports.parameter-report :dataSource="$dataModeControl" :itemsSelected="$modeParams" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    </div>
    @if(!empty($legendColors))
    <x-renderer.card class="mb-5">
        <x-reports.color-legend-report :dataSource="$legendColors" />
    </x-renderer.card>
    @endif
    <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" showNo={{true}} rotate45Width={{$rotate45Width}} groupBy="{{$groupBy}}" groupByLength="{{$groupByLength}}" />
</div>
@endsection
