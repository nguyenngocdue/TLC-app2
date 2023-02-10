@extends('layouts.app')

@section('topTitle', 'Reports')
@section('title', 'Manage Workflow')

{{-- @dump($tableDataSource) --}}

@section('content')
<x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" groupKeepOrder="{{true}}" groupBy="group_description" groupByLength=100 showNo="{{true}}" />
@endsection
