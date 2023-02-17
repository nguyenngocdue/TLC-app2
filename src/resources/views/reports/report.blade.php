@extends('layouts.app')

@section('topTitle',$typeReport)
@section('title', $entity)

@php
$dataSource = ['sub_project_id' => $subProjects,'prod_orders' => $prod_orders];
@endphp

@section('content')
<div class="px-4">
    <x-renderer.modes-control :dataSource="$dataSource" :itemsSelected="$urlParams" />
    <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" />
    @endsection
</div>
