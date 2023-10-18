@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')

@php
$class1 = 'p-2 border h-full w-full flex border-gray-600 text-base font-medium bg-gray-50 items-center justify-end';
$class2 = 'p-2 border border-gray-600 flex justify-start items-center text-sm font-normal text-left';
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



{{-- RENDER TABLES --}}
<div class="flex justify-center bg-only-print">
{{-- @dd($tableDataSource) --}}
    <div class="md:px-4">
                @php
                    $widgets = $tableDataSource['widgets']
                @endphp

            <div style='' class="{{$layout}} items-center bg-white box-border p-8">
                <div class="py-5">
                    <x-print.header6 />
                    <x-renderer.heading level=2 class='text-center pt-10'>Non Conformance Reports</x-renderer.heading>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-2 gap-2">
                        <div class="p-6">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>All Issues by Status</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_status" :data="$widgets['ncr_status']" ></x-renderer.report.pivot-chart2>
                        </div>
                        <div class="p-6">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>Count of Responsible Teams</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_user_team" :data="$widgets['ncr_user_team']" ></x-renderer.report.pivot-chart2>
                        </div>
                       
                </div>
            </div>
            <x-renderer.page-break />
            <div style='' class="{{$layout}} items-center bg-white box-border p-8">
                <div class="grid grid-cols-2 md:grid-cols-2 gap-2">
                        <div class="p-6">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>Severity of Open Issues</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_severity" :data="$widgets['ncr_severity']" ></x-renderer.report.pivot-chart2>
                        </div>
                         <div class="p-6">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>Count of Report Type</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_report_type" :data="$widgets['ncr_report_type']" ></x-renderer.report.pivot-chart2>
                        </div>
                        <div class="p-6">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>Count of Source Form</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_parent_type" :data="$widgets['ncr_parent_type']" ></x-renderer.report.pivot-chart2>
                        </div>
                         <div class="p-6">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>Priority of Open Issues</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_priority" :data="$widgets['ncr_priority']" ></x-renderer.report.pivot-chart2>
                        </div>
                </div>
            </div>
    </div>
</div>
@endsection
