@extends('layouts.app')

@section('topTitle',$typeReport)
@section('title', $entity)
{{-- @dump($tableDataSource, $tableColumns) --}}

@section('content')
<div class="px-4">
    <x-renderer.modes-control :dataSource="$dataModeControl" :itemsSelected="$urlParams" />
    <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" showNo={{true}} />
</div>
@endsection
