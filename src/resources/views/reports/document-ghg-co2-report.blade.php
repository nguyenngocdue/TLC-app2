@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')

{{-- PARAMETERS --}}
@php
$widthCell = 50;
$class1 = "bg-white dark:border-gray-600 border-r";
$class2 =" bg-gray-100 px-4 py-3 border-gray-300 ";
$titleColName = isset($params['quarter_time']) ? 'QTR'.$params['quarter_time'] : 'YTD';
$titleColName = isset($params['only_month']) ? 'Total Quantity': $titleColName;
$year = $params['year'];
$data = $tableDataSource['carbon_footprint'][$year];
$pivotChart1 = $tableDataSource['pivot_chart_1'];
$pivotChart2 = $tableDataSource['pivot_chart_2'];
$info = $tableDataSource['info'];
@endphp

{{-- @dump($tableDataSource) --}}

<div class="px-4">
    <div class="justify-end pb-5"></div>
    <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
        <x-reports.parameter3-report :itemsSelected="$params" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    </div>
</div>
<br />
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='page-break-after:always!important' class="w-[1000px] min-h-[1360px] items-center bg-white box-border p-8">
            <x-print.header6 />
            <div class="pb-3">
                <x-renderer.heading level=2 xalign='center'>CO2 Report</x-renderer.heading>
                <x-renderer.heading level=4 xalign='center'>for TLC Modular Construction Limited Liability Company</x-renderer.heading>
            </div>
            <x-renderer.heading level=3 xalign='left' class='text-blue-600'>Company's carbon footprint in the year {{$year}}</x-renderer.heading>
            <div class="grid grid-rows-1 pt-20">
                <div class="grid grid-cols-12 text-center">
                    <div class="col-span-6 m-auto">
                        <div class='w-60 h-60 bg-green-700 p-3 flex justify-between flex-col '>
                            <h3 class='text-xl font-semibold text-white'>Company Carbon Footprint</h3>
                            <h4 class='text-4xl font-semibold text-white'>{{$data['total_emission']}}</h4>
                            <h2 class='text-sm text-white'>tCO2e</h2>
                        </div>
                    </div>
                    <div class="col-span-6 m-auto">
                        <div class='w-60 h-60 bg-green-700 p-3 flex justify-between flex-col '>
                            <h3 class='text-xl font-semibold text-white'>Carbon per employee</h3>
                            <h4 class='text-4xl font-semibold text-white'>{{$data['co2_footprint_employee']}}</h4>
                            <h2 class='text-sm text-white'>average tCO2e/FTE</h2>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <x-renderer.heading level=3 xalign='left' class='text-blue-600 font-semibold'>Chart</x-renderer.heading> --}}
            <div class=" grid-rows-1 pt-20 flex justify-center flex-col items-center">
                <div class="w-1/2">
                    <x-renderer.report.pivot-chart key="carbon_footprint_1" :dataSource="$pivotChart1"></x-renderer.report.pivot-chart>
                </div>
                <x-renderer.heading level=6 xalign='left' class='text-blue-600 font-semibold'>Company's direct emissions in the year amounted to <strong>{{$info['direct_emissions']}}</strong> tCO2e. Indirect emissions from purchased energy
                    accounted for <strong>{{$info['indirect_emissions']}}</strong> tCO2e and other indirect emissions generated in the company's value chain were <strong>{{$info['other_indirect_emissions']}}</strong> tCO2e.</x-renderer.heading>
            </div>
        </div>
        <x-renderer.page-break />
        <div style='page-break-after:always!important' class="w-[1000px] min-h-[1360px] items-center bg-white box-border p-8">
            <x-renderer.heading level=3 xalign='left' class='text-blue-600'>Emission source category chart</x-renderer.heading>
            <div class=" grid-rows-1 pt-20 flex justify-center flex-col items-center">
                <div class="w-full h-1/2">
                    <x-renderer.report.pivot-chart key="carbon_footprint_2" :dataSource="$pivotChart2"></x-renderer.report.pivot-chart>
                </div>
                <x-renderer.heading level=6 xalign='left' class='text-blue-600 font-semibold'>Company's direct emissions in the year amounted to <strong>{{$info['direct_emissions']}}</strong> tCO2e. Indirect emissions from purchased energy
                    accounted for <strong>{{$info['indirect_emissions']}}</strong> tCO2e and other indirect emissions generated in the company's value chain were <strong>{{$info['other_indirect_emissions']}}</strong> tCO2e.</x-renderer.heading>
            </div>
        </div>
    </div>
</div>
@endsection
