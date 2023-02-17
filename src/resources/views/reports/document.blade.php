@extends('layouts.app')
@section('topTitle',$typeReport)
@section('title', $entity)
@section('content')

@php
$dataSource = ['sub_project_id' => $subProjects,'prod_order_id' => $prod_orders /* ,'filter_run' => ['Filter for a latest run', 'Filter for many runs'] */];
@endphp

@section('content')
<div class="px-4">
    <x-renderer.modes-control :dataSource="$dataSource" :itemsSelected="$urlParams" />
</div>
<div class="px-4">
    <x-renderer.table maxH="{{false}}" :dataSource="$sheets" :columns="[['dataIndex' => key(array_pop($sheets))]]" showNo="{{true}}" />
    <x-renderer.divider />
    <x-renderer.page-break />
    <x-renderer.divider />
    @foreach($tableDataSource as $idSheet => $data)
    @php
    @endphp
    <x-renderer.report.header-report :dataSource="array_pop($data)" />
    <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$data" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
    <x-renderer.page-break />
    @endforeach
</div>
@endsection
