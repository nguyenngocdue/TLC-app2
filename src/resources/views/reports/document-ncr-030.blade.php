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
$tc = "
<x-reports.utility-report routeName='$routeName' />";
$tr = "
<x-reports.per-page-report typeReport='$typeReport' entity='$entity' routeName='$routeName' page-limit='$pageLimit' formName='updatePerPage' />";
@endphp

<div class="px-4">
    @include('components.reports.shared-parameter')
    @include('components.reports.show-layout', ['optionPrint' => $optionPrint])
</div>
{{-- RENDER TABLES --}}
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='page-break-after:always!important' class="{{$layout}} items-center bg-white box-border p-8">
            <x-print.header6 />
            {{-- BASIC INFORMATION --}}
            <x-renderer.heading level=2 class='text-center'>NCR/DR Report Monthly Report</x-renderer.heading>

            {{-- TABLES --}}
            <div class="">
                <x-renderer.heading level=4 class='text-center'>Monthly Opened/Closed Issues</x-renderer.heading>
                <iframe class="ml-80" src="https://grafana.tlcmodular.com/d-solo/e6d647c2-948d-46b6-a0d6-5f444ba8125f/non-conformance-reports?orgId=1&from=1658941200000&to=1706547599000&panelId=2" width="500" height="500" frameborder="0"></iframe>

                <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300 pt-4" id="" title="" style="scroll-margin-top: 90px;">Detail Report</h4>
                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['OPEN_CLOSED_ISSUES']" :dataSource="$tableDataSource['OPEN_CLOSED_ISSUES']" maxH='{{$maxH}}' page-limit="{{$pageLimit}}" tableTrueWidth={{$tableTrueWidth?1:0}} />
            </div>

            <div class="">
                <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300 pt-4" id="" title="" style="scroll-margin-top: 90px;">Detail Report</h4>
                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['NCR_DR']" :dataSource="$tableDataSource['NCR_DR']" maxH='{{$maxH}}' page-limit="{{$pageLimit}}" tableTrueWidth={{$tableTrueWidth?1:0}} />
            </div>

            <div class="">
                <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300 pt-4" id="" title="" style="scroll-margin-top: 90px;">Detail Report</h4>
                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['RESPONSIBLE_TEAM']" :dataSource="$tableDataSource['RESPONSIBLE_TEAM']" maxH='{{$maxH}}' page-limit="{{$pageLimit}}" tableTrueWidth={{$tableTrueWidth?1:0}} />
            </div>

            <div class="">
                <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300 pt-4" id="" title="" style="scroll-margin-top: 90px;">Detail Report</h4>
                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['ISSUES_STATUS']" :dataSource="$tableDataSource['ISSUES_STATUS']" maxH='{{$maxH}}' page-limit="{{$pageLimit}}" tableTrueWidth={{$tableTrueWidth?1:0}} />
            </div>

            <div class="">
                <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300 pt-4" id="" title="" style="scroll-margin-top: 90px;">Detail Report</h4>
                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['ISSUES_SOURCE']" :dataSource="$tableDataSource['ISSUES_SOURCE']" maxH='{{$maxH}}' page-limit="{{$pageLimit}}" tableTrueWidth={{$tableTrueWidth?1:0}} />
            </div>

        </div>
    </div>
</div>
@endsection
