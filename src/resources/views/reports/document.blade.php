@extends('layouts.app')

@section('topTitle',$typeReport)
@section('title', $entity)
@section('content')

@php
$nameControls = array_keys($urlParams);
$controlName1 = isset($nameControls[0]) ? $nameControls[0] : "NO";
$dataSource = [$controlName1 => $prod_orders];
@endphp

@section('content')
<div class="px-4">
    <x-renderer.modes-control :dataSource="$dataSource" :itemsSelected="$urlParams" />
</div>
<div class="px-4">
    <x-renderer.table-report :dataSource="$sheets"></x-renderer.table-report>
    <x-renderer.divider />
    <x-renderer.page-break />
    <x-renderer.divider />
    @foreach($tableDataSource as $idSheet => $data)
    @php
    $item = $data[0];
    @endphp
    <x-renderer.report.header-report :dataSource="$item"></x-renderer.report.header-report>
    <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$data" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
    <x-renderer.page-break />
    @endforeach
</div>
@endsection
