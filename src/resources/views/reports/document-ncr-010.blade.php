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

@php
        $layout = '';
        switch ($optionPrint) {
            case 'landscape':
            $layout = 'w-[1400px] min-h-[1000px]';
            break;
            case 'portrait':
            default:
                $layout = 'w-[1000px] min-h-[880px]';
                break;
        }
@endphp

<div class="px-4">
    @include('components.reports.shared-parameter')
    {{-- @include('components.reports.show-layout2') --}}
</div>

{{-- RENDER TABLES --}}
<div class="flex justify-center bg-only-print">
{{-- @dd($tableDataSource) --}}
    <div class="md:px-4">
                @php
                    $widgets = $tableDataSource['widgets']
                @endphp

            <div style='' class="{{$layout}} items-center bg-white box-border p-8">
                <div class="pt-5">
                    <x-print.header6 />
                    <x-renderer.heading level=2 class='text-center pt-4'>Non Conformance Reports</x-renderer.heading>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-2 gap-2">
                        <div class="flex flex-col justify-between">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>All Issues by Discipline</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_discipline" :data="$widgets['ncr_discipline']" ></x-renderer.report.pivot-chart2>
                            <x-renderer.heading level=6 xalign='center' class='text-gray-600 font-semibold '>Count the number of issues based on <strong>Discipline</strong></x-renderer.heading>
                        </div>
                        <div class="flex flex-col justify-between">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>All Issues by Internal/External</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_inter_subcon" :data="$widgets['ncr_inter_subcon']" ></x-renderer.report.pivot-chart2>
                            <x-renderer.heading level=6 xalign='center' class='text-gray-600 font-semibold '>Count the number of issues based on <strong>nternal/External</strong></x-renderer.heading>
                        </div>
                        <div class="flex flex-col justify-between">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>All Issues by Root Cause</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_root_cause" :data="$widgets['ncr_root_cause']" ></x-renderer.report.pivot-chart2>
                            <x-renderer.heading level=6 xalign='center' class='text-gray-600 font-semibold '>Count the number of issues based on <strong>Root Cause</strong></x-renderer.heading>
                        </div>
                        <div class="flex flex-col justify-between">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>All Issues by Disposition</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_disposition" :data="$widgets['ncr_disposition']" ></x-renderer.report.pivot-chart2>
                            <x-renderer.heading level=6 xalign='center' class='text-gray-600 font-semibold '>Count the number of issues based on <strong>Disposition</strong></x-renderer.heading>
                        </div>
                </div>
            </div>
            <x-renderer.page-break />
            
            <div style='' class="{{$layout}} items-center bg-white box-border p-8">
                <div class="pt-5">
                    <x-print.header6 />
                    <x-renderer.heading level=2 class='text-center pt-4'>Non Conformance Reports</x-renderer.heading>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-2 gap-2">
                        <div class="flex flex-col justify-between">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>All Issues by Status</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_status" :data="$widgets['ncr_status']" ></x-renderer.report.pivot-chart2>
                             <x-renderer.heading level=6 xalign='center' class='text-gray-600 font-semibold '>Count the number of issues based on <strong>statuses of the NCR</strong></x-renderer.heading>
                        </div>
                        <div class="flex flex-col justify-between">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>All Issues by Responsible Teams</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_user_team" :data="$widgets['ncr_user_team']" ></x-renderer.report.pivot-chart2>
                             <x-renderer.heading level=6 xalign='center' class='text-gray-600 font-semibold '>Count the number of issues based on <strong>user team NCR</strong></x-renderer.heading>
                        </div>
                        <div class="flex flex-col justify-between">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>All Issues of Source Form</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_parent_type" :data="$widgets['ncr_parent_type']" ></x-renderer.report.pivot-chart2>
                             <x-renderer.heading level=6 xalign='center' class='text-gray-600 font-semibold '>Count the number of issues based on <strong>the type of other forms</strong></x-renderer.heading>
                        </div>
                        <div class="flex flex-col justify-between">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>Severity of Open Issues</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_severity" :data="$widgets['ncr_severity']" ></x-renderer.report.pivot-chart2>
                             <x-renderer.heading level=6 xalign='center' class='text-gray-600 font-semibold '>Count the number of issues based on <strong>Severity</strong></x-renderer.heading>
                        </div>
                       
                </div>
            </div>
            <x-renderer.page-break />
            <div style='' class="{{$layout}} items-center bg-white box-border p-8">
                <div class="pt-5">
                    <x-print.header6 />
                    <x-renderer.heading level=2 class='text-center pt-4'>Non Conformance Reports</x-renderer.heading>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-2 gap-2">
                         <div class="p-6">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>Type of Open Issues</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_report_type" :data="$widgets['ncr_report_type']" ></x-renderer.report.pivot-chart2>
                            <x-renderer.heading level=6 xalign='center' class='text-gray-600 font-semibold '>Count the number of <strong>Report Type</strong></x-renderer.heading>
                        </div>
                         <div class="p-6">
                            <x-renderer.heading level=5 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>Priority of Open Issues</x-renderer.heading>
                            <x-renderer.report.pivot-chart2 key="ncr_priority" :data="$widgets['ncr_priority']" ></x-renderer.report.pivot-chart2>
                             <x-renderer.heading level=6 xalign='center' class='text-gray-600 font-semibold '>Count the number of issues based on <strong>Priority</strong></x-renderer.heading>
                        </div>
                </div>
            </div>
            <x-renderer.page-break />
    </div>
</div>
@endsection
