@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $typeReport)
@section('content')
<div class="px-4 ">
    <div class="flex justify-end pb-2 pr-4">
        <x-reports.per-page-report typeReport="{{$typeReport}}" entity="{{$entity}}" routeName="{{$routeName}}" page-limit="{{$pageLimit}}" />
    </div>
    <x-reports.parameter-report :dataSource="$dataModeControl" :itemsSelected="$modeParams" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    <x-renderer.card class="mb-5">
        <x-reports.color-legend-report :dataSource="$legendColors" />
    </x-renderer.card>
    <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource" showNo={{true}} rotate45Width={{$rotate45Width}} groupBy="{{$groupBy}}" />
</div>
@endsection
