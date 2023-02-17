@extends('layouts.app')

@section('topTitle',$typeReport)
@section('title', $entity)

@php
$nameControls = array_keys($urlParams);
$controlName1 = isset($nameControls[0]) ? $nameControls[0] : "NO";
$controlName2 = isset($nameControls[1]) ? $nameControls[1] : "No";
$dataSource = [$controlName1 => $subProjects, $controlName2 =>$prod_orders];
// $hiddenItems = ["chklst"];
@endphp

@section('content')
<div class="px-4">
    <x-renderer.modes-control :dataSource="$dataSource" :itemsSelected="$urlParams" />
    <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" />
</div>
@endsection
