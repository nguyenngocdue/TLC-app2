@extends('layouts.app')
@section('topTitle',$typeReport)
@section('title', $entity)
@section('content')

{{-- @dump($tableDataSource) --}}
@section('content')
<div class="md:px-4">
    <x-renderer.parameter-control :dataSource="$dataModeControl" :itemsSelected="$urlParams" />
</div>
<div class="md:px-4">
    @if (count($sheets))
    <x-renderer.table maxH="{{false}}" :dataSource="$sheets" :columns="[['dataIndex' => key(array_pop($sheets))]]" showNo="{{true}}" />
    <x-renderer.divider />
    <x-renderer.page-break />
    <x-renderer.divider />
    @endif
    @foreach($tableDataSource as $idSheet => $data)
    <x-renderer.report.header-report :dataSource="array_pop($data)" />
    <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource[$idSheet]" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
    <x-renderer.page-break />
    @endforeach
</div>
@endsection
