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
    $tc = "<x-reports.utility-report routeName='$routeName'/>"; 
    $tr = "<x-reports.per-page-report typeReport='$typeReport' entity='$entity' routeName='$routeName' page-limit='$pageLimit' formName='updatePerPage' />"; 
@endphp

<div class="px-4">
    @include('components.reports.shared-parameter')
    @include('components.reports.show-layout2')
</div>
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='page-break-after:always!important' class="{{$layout}} items-center bg-white box-border p-8">
            <x-print.header6 />
            <x-renderer.heading level=2 class='text-center pt-4'>QAQC Acceptance Report</x-renderer.heading>
            {{-- RENDER TABLES --}}
            <div class="pt-4">
                <x-renderer.heading level=6 class='text-right italic'>Date od Update: <strong>{{$basicInfoData['date_of_update']}} </strong></x-renderer.heading>
                <x-renderer.report.pivot-table 
                    showNo={{true}} 
                    :tableColumns="$tableColumns" 
                    :dataSource="$tableDataSource"
                    page-limit="{{$pageLimit}}"
                     />
            </div>
        </div>


    </div>
</div>
@endsection
