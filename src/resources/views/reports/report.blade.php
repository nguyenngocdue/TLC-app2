@extends('layouts.app')

@section('topTitle',$typeReport)
@section('title', $entity)

@php
$nameControls = array_keys($urlParams);
$nameControl1 = isset($nameControls[0]) ? $nameControls[0] : "NO";
$nameControl2 = isset($nameControls[1]) ? $nameControls[1] : "No";
@endphp

@section('content')
<x-renderer.modes-report :dataControl1="$subProjects" :dataControl2="$prod_orders" nameControl1="{{$nameControl1}}" nameControl2="{{$nameControl2}}" :itemsSelected="$urlParams"></x-renderer.modes-report>
<x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" />
@endsection
