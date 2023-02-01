@extends('layouts.app')

@section('topTitle', 'Reports')
@section('title', 'Manage Workflow')

@section('content')
<x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" />
@endsection
