@extends('layouts.app')

@section('topTitle',$typeReport)
@section('title', $entity)

{{-- @dump($tableDataSource) --}}


@section('content')


@php
$nameControls = array_keys($urlParams);
$nameControl1 = isset($nameControls[0]) ? $nameControls[0] : "NO";

// dd($prod_orders)

@endphp



@section('content')
<x-renderer.modes-report :controlValues="[$prod_orders]" :controlNames="[$nameControl1]" :itemsSelected="$urlParams"></x-renderer.modes-report>
<x-renderer.table-report :dataSource="$sheets"></x-renderer.table-report>
@foreach($tableDataSource as $idSheet => $data)
<x-renderer.table :columns="$tableColumns" :dataSource="$data" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
<br />
<br />
<x-renderer.page-break></x-renderer.page-break>
<br />
<br />
@endforeach
@endsection
