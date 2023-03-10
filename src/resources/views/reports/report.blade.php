@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $typeReport)
@section('content')

{{-- @dd($tableDataSource, $tableColumns); --}}
<div class="px-4">
    <div class="flex justify-end pb-2 pr-4">
        <x-form.per-page-report typeReport="{{$typeReport}}" entity="{{$entity}}" routeName="{{$routeName}}" page-limit="{{$pageLimit}}" />
    </div>
    <x-form.parameter-report :dataSource="$dataModeControl" :itemsSelected="$modeParams" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" />
    @endsection
</div>
