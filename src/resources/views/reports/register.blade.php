@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $modeReport)
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
    <div class="flex pb-2 pr-4 ">
        <x-reports.utility-report routeName="{{$routeName}}" />
        <x-reports.per-page-report typeReport="{{$typeReport}}" entity="{{$entity}}" routeName="{{$routeName}}" page-limit="{{$pageLimit}}" formName="updatePerPage" />
    </div>
    @php
    $tc = "<x-renderer.tag>Top Center</x-renderer.tag> ";
    @endphp
    {{-- <x-renderer.tag>Top Center</x-renderer.tag> --}}
    <x-renderer.table  :dataHeader="$tableDataHeader" topCenterControl="{!!$tc!!}" :columns="$tableColumns" :dataSource="$tableDataSource" showNo={{true}} maxH="{{$maxH}}" rotate45Width={{$rotate45Width}} groupBy="{{$groupBy}}" groupByLength="{{$groupByLength}}" tableTrueWidth_disabled={{$tableTrueWidth?1:0}} />
</div>
@endsection
