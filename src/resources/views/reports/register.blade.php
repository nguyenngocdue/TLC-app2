@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $typeReport)
@section('content')
<div class="px-4 ">
    <div class="flex justify-end pb-2 pr-4">
        <x-reports.per-page-report typeReport="{{$typeReport}}" entity="{{$entity}}" routeName="{{$routeName}}" page-limit="{{$pageLimit}}" />
    </div>
    <x-reports.mode-report :dataSource="$modeOptions" formName="mode_options" :itemsSelected="['mode_option' =>$currentMode]" userId="{{$currentUserId}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />

    @switch($currentMode)
    @case('010')
    <x-reports.parameter-report :dataSource="$dataModeControl" :itemsSelected="$modeParams" modeOption="010" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    @break

    @default

    @endswitch
    <x-renderer.card class="mb-5">
        <x-reports.color-legend-report :dataSource="$legendColors" />
    </x-renderer.card>
    <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource" showNo={{true}} rotate45Width={{$rotate45Width}} groupBy="{{$groupBy}}" />
</div>
@endsection
