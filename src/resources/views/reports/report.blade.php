@extends('layouts.app')

@section('topTitle',$typeReport)
@section('title', $entity)
@section('content')

{{-- @dd($tableDataSource, $tableColumns); --}}
<div class="px-4">
    <x-renderer.modes-control :dataSource="$dataModeControl" :itemsSelected="$urlParams" />
    <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" />
    @endsection
</div>
