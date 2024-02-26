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
{{-- Parameters for Charts --}}
@php
    $width = "100%";
    $heigh = 300;
    $year = $paramsGrafana['year'];
    $centerURL = $paramsGrafana['prams_url_str'];
    $subUrl = "https://grafana.tlcmodular.com/d-solo/ea031cad-a2b2-455b-adf8-ed50d990bfae/ncr?orgId=1&".$centerURL;
    //dump($subUrl);
@endphp


@php
    $layout = '';
    switch ($optionPrint) {
        case 'landscape':
            $layout = 'w-[1100px] h-min-[1000px]';
            break;
        case 'portrait':
        default:
            $layout = 'w-[1400px] h-min-[1000px]';
            break;
    }
@endphp



<div class="px-4">
    @include('components.reports.shared-parameter')
    @include('components.reports.show-layout2', ['optionPrint' => $optionPrint])
</div>



{{-- RENDER TABLES --}}
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='' class="  {{$layout}} items-center bg-white p-8">
            <x-print.header6 />
            {{-- BASIC INFORMATION --}}
            <x-renderer.heading level=2 class='text-center'>NCR/DR Monthly Report</x-renderer.heading>

            {{-- TABLES --}}
            <div class='grid grid-row-1 gap-4'>
                <div class='grid grid-cols-12 gap-4 text-center'>
                    <div class='col-span-6'>
                        <x-renderer.heading level=4 class='text-center'>Monthly Opened/Closed Issues</x-renderer.heading>
                        <div class="flex flex-col text-center">
                            <iframe class="self-center" src="{{$subUrl}}&from=1708395847141&to=1708417447141&theme=light&&panelId=1" width="{{$width}}" height="{{$heigh}}" frameborder="1">
                            </iframe>
                            <div class='pt-4'>
                                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['OPEN_CLOSED_ISSUES']" :dataSource="$tableDataSource['OPEN_CLOSED_ISSUES']" maxH='{{$maxH}}' page-limit="{{$pageLimit}}" tableTrueWidth={{$tableTrueWidth?1:0}} />
                            </div>
                        </div>
                    </div>
                    <div class='col-span-6'>
                        <x-renderer.heading level=4 class='text-center'>Monthly NCR/DR Quantity</x-renderer.heading>
                        <div class="flex flex-col text-center">
                            <iframe class="self-center" src="{{$subUrl}}&from=1708395847141&to=1708417447141&theme=light&&panelId=2" width="{{$width}}" height="{{$heigh}}" frameborder="1">
                            </iframe>
                            <div class='pt-4'>
                                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['NCR_DR']" :dataSource="$tableDataSource['NCR_DR']" maxH='{{$maxH}}' page-limit="{{$pageLimit}}" tableTrueWidth={{$tableTrueWidth?1:0}} />
                            </div>
                        </div>
                    </div>

                     <div class='col-span-12'>
                        <x-renderer.heading level=4 class='text-center'>Monthly Issues' Statuses</x-renderer.heading>
                        <div class="flex flex-col text-center">
                            <iframe class="self-center" src="{{$subUrl}}&from=1708395847141&to=1708417447141&theme=light&&panelId=5" width="990px" height="{{$heigh}}" frameborder="1">
                            </iframe>
                            <div class='pt-4'>
                                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['ISSUES_STATUS']" :dataSource="$tableDataSource['ISSUES_STATUS']" maxH='{{$maxH}}' page-limit="{{$pageLimit}}" tableTrueWidth={{$tableTrueWidth?1:0}} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <x-renderer.page-break />

        <div style='{{-- page-break-after:always!important --}}' class="{{$layout}} items-center bg-white  p-8">
            {{-- <x-print.header6 /> --}}
            {{-- <x-renderer.heading level=2 class='text-center'>NCR/DR Monthly Report</x-renderer.heading> --}}
            <div class='grid grid-row-1 gap-4'>
                <div class='grid grid-cols-12 gap-4 text-center'>
                    
                     <div class='col-span-6'>
                        <x-renderer.heading level=4 class='text-center'>Monthly Issues Source</x-renderer.heading>
                        <div class="flex flex-col text-center">
                            <iframe class="self-center" src="{{$subUrl}}&from=1708395847141&to=1708417447141&theme=light&&panelId=6" width="{{$width}}" height="{{$heigh}}" frameborder="1">
                            </iframe>
                            <div class='pt-4'>
                                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['ISSUES_SOURCE']" :dataSource="$tableDataSource['ISSUES_SOURCE']" maxH='{{$maxH}}' page-limit="{{$pageLimit}}" tableTrueWidth={{$tableTrueWidth?1:0}} />
                            </div>
                        </div>
                    </div>

                    <div class='col-span-6'>
                        <x-renderer.heading level=4 class='text-center'>Average Closed Days by Monthly Issues</x-renderer.heading>
                        <div class="flex flex-col text-center">
                            <iframe class="self-center" src="{{$subUrl}}&from=1708395847141&to=1708417447141&theme=light&&panelId=4" width="{{$width}}" height="{{$heigh}}" frameborder="1">
                            </iframe>
                            <div class='pt-4'>
                                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['AVERAGE_CLOSED_ISSUES']" :dataSource="$tableDataSource['AVERAGE_CLOSED_ISSUES']" maxH='{{$maxH}}' page-limit="{{$pageLimit}}" tableTrueWidth={{$tableTrueWidth?1:0}} />
                            </div>
                        </div>
                    </div>

                     <div class='col-span-12'>
                        <x-renderer.heading level=4 class='text-center'>Monthly Responsible Team's Issues</x-renderer.heading>
                        <div class="flex flex-col text-center">
                            <iframe class="self-center" src="{{$subUrl}}&from=1708395847141&to=1708417447141&theme=light&&panelId=3" width="990px" height="{{$heigh}}" frameborder="1">
                            </iframe>
                            <div class='pt-4'>
                                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['RESPONSIBLE_TEAM']" :dataSource="$tableDataSource['RESPONSIBLE_TEAM']" maxH='{{$maxH}}' page-limit="{{$pageLimit}}" tableTrueWidth={{$tableTrueWidth?1:0}} />
                            </div>
                        </div>
                    </div>

                   

                </div>
            </div>
        </div>

    </div>
</div>
@endsection