@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $typeReport)
@php
@endphp
@section('content')
<div class="px-4 ">
    <div class="flex justify-end pb-2 pr-4">
        <x-form.per-page-report typeReport="{{$typeReport}}" entity="{{$entity}}" route="{{ route('updateUserSettings') }}" page-limit="{{$pageLimit}}" />
    </div>
    <x-form.parameter-report :dataSource="$dataModeControl" :itemsSelected="$modeParams" :columns="$paramColumns" route="{{ route('updateUserSettings') }}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    <x-renderer.table maxH="{{false}}" :columns="$tableColumns" :dataSource="$tableDataSource" showNo={{true}} rotate45Width={{$rotate45Width}} />
</div>
@endsection
