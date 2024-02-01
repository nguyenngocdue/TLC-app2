@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')

@php
$class1 = 'p-2 border h-full w-full flex border-gray-600 text-base font-medium bg-gray-50 items-center justify-end';
$class2 = 'p-2 border border-gray-600 flex justify-start items-center text-sm font-normal text-left'
@endphp

{{-- "Show utility"  --}}
@php
$tl = "<div></div>";
$tc = "
<x-reports.utility-report routeName='$routeName' />";
$tr = "
<x-reports.per-page-report typeReport='$typeReport' entity='$entity' routeName='$routeName' page-limit='$pageLimit' formName='updatePerPage' />";
@endphp

<div class="px-4">
    @include('components.reports.shared-parameter')
    {{-- @include('components.reports.show-layout') --}}
</div>

<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='' class="max-w-[1800px] min-h-[990px] items-center bg-white box-border p-8">
            <div class="pt-5">
                @include('reports.include-table-esg-master-sheet-020')
            </div>
        </div>
        <x-renderer.page-break />
    </div>
</div>
@endsection
