{{-- @props(["a"]); --}}
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
    @include('components.reports.shared-parameter')
    {{-- @include('components.reports.show-layout2') --}}
</div>
@php
        $layout = '';
        switch ($optionPrint) {
            case 'landscape':
            $layout = 'w-[1400px] min-h-[940px]';
            break;
            case 'portrait':
                $layout = 'w-[1000px] min-h-[1450px]';
                break;
            default:
                break;
        }
@endphp

{{-- @dd($entity) --}}
<br />
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='' class="{{$layout}} items-center bg-white box-border px-8 py-6 relative">
            <x-print.header6 :itemsShow="['logo']"/>
            <div class="py-5">
                <x-renderer.heading level=1 xalign='center'>CO2 Emission Report</x-renderer.heading>
                <x-renderer.heading level=3 xalign='center'>for TLC Modular Construction Limited Liability Company</x-renderer.heading>
            </div>
            <x-renderer.heading level=3 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-2'>Company's carbon footprint in the year {{$year}}</x-renderer.heading>
            <div class="grid grid-rows-1 pt-20">
                <div class="grid grid-cols-12 text-center">
                    <div class="col-span-6 m-auto">
                        <div class='w-96 h-96 border-2 bg-green-700 p-3 flex justify-between flex-col '>
                            <h3 class='text-3xl font-semibold text-white'>Company Carbon Footprint</h3>
                            <h4 class='text-6xl font-semibold text-white'>{{$data['total_emission']}}</h4>
                            <h2 class='text-lg text-white'>tCO2e</h2>
                        </div>
                    </div>
                    <div class="col-span-6 m-auto">
                        <div class='w-96 h-96 border-2 bg-violet-600 p-3 flex justify-between flex-col '>
                            <h3 class='text-3xl font-semibold text-white'>Carbon Footprint per Employee</h3>
                            <h4 class='text-6xl font-semibold text-white'>{{$data['co2_footprint_employee']}}</h4>
                            <h2 class='text-lg text-white'>average tCO2e/FTE</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full pb-4 absolute bottom-0 right-0 left-0 flex flex-row-reverse justify-center">
                <x-print.header6 :itemsShow="['website']"/>
            </div>
        </div>
        <x-renderer.page-break />
        {{-- chart 1 --}}
        <div class="relative {{$layout}} items-center bg-white box-border px-8 py-6">
            <div style='' class="">
                <x-renderer.heading level=3 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-2'>Emission source category chart</x-renderer.heading>
                <div class=" grid-rows-1 pt-10 flex justify-center flex-col items-center">
                    <div class="w-full flex px-4">
                        <div class="w-1/2 px-4">
                            <x-renderer.report.pivot-chart key="carbon_footprint_1" :dataSource="$pivotChart1" showValue={{false}}></x-renderer.report.pivot-chart>
                        </div>
                        <div class="m-auto">
                            @php
                                $scopeNames = array_column($pivotChart1['scopeNames'], 'name');
                            @endphp

                            <x-renderer.card title="Carbon Emissions" tooltip="">
                                @if(in_array('Scope 1', $scopeNames))
                                    <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">Scope 1 (Direct Emission)</h5>
                                    <p class="font-normal text-gray-700 dark:text-gray-400">
                                        Emissions from sources that an organisation owns or controls directly.
                                    </p>
                                @endif

                                @if(in_array('Scope 2', $scopeNames))
                                    <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">Scope 2 (Indirect Emission)</h5>
                                    <p class="font-normal text-gray-700 dark:text-gray-400">
                                        Emissions a company causes indirectly that come from where the energy it purchases and uses is produced.
                                    </p>
                                @endif

                                @if(in_array('Scope 3', $scopeNames))
                                    <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">Scope 3 (Indirect Emission)</h5>
                                    <p class="font-normal text-gray-700 dark:text-gray-400">
                                        All emissions not covered in scope 1 or 2, created by a company's value chain.
                                    </p>
                                @endif
                            </x-renderer.card>
                        </div>
                    </div>

                     <ul class="list-disc flex flex-col items-start">
                        <li>
                        {{-- @dd($info) --}}
                            <x-renderer.heading level=5 xalign='center' class='text-gray-600 font-semibold'>Company's direct emissions in the year amounted to <strong>{{$info['direct_emissions']['tco2e']}}</strong> tCO2e ( <strong>{{$info['direct_emissions']['percent']}}%</strong>).</x-renderer.heading>
                        </li>
                        <li>
                            <x-renderer.heading level=5 xalign='center' class='text-gray-600 font-semibold'>Indirect emissions from purchased energy accounted for <strong>{{$info['indirect_emissions']['tco2e']}}</strong> tCO2e ( <strong>{{$info['indirect_emissions']['percent']}}%</strong>).</x-renderer.heading>
                        </li>
                        <li>
                            <x-renderer.heading level=5 xalign='center' class='text-gray-600 font-semibold'>Other indirect emissions generated in the company's value chain were <strong>{{$info['other_indirect_emissions']['tco2e']}}</strong> tCO2e ( <strong>{{$info['other_indirect_emissions']['percent']}}%</strong>).</x-renderer.heading>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-full pb-4 absolute bottom-0 right-0 left-0 flex flex-row-reverse justify-center">
                    <x-print.header6 :itemsShow="['website']"/>
            </div>
        </div>
            <x-renderer.page-break />
        <div class="relative {{$layout}} items-center bg-white box-border px-8 py-6 ">
            {{-- chart 2 --}}
            <div style='' class="">
                <x-renderer.heading level=3 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-2'>Emission source category chart</x-renderer.heading>
                <div class=" grid-rows-1 pt-4 flex justify-center flex-col items-center">
                    <div class="w-full px-6">
                    {{-- @dump($pivotChart2) --}}
                        <x-renderer.report.pivot-chart key="carbon_footprint_2" :dataSource="$pivotChart2"></x-renderer.report.pivot-chart>
                    </div>
                    <ul class="list-disc flex flex-col items-start">
                        <li>
                            <x-renderer.heading level=5 xalign='center' class='text-gray-600 font-semibold'>Company's direct emissions in the year amounted to <strong>{{$info['direct_emissions']['tco2e']}}</strong> tCO2e.</x-renderer.heading>
                        </li>
                        <li>
                            <x-renderer.heading level=5 xalign='center' class='text-gray-600 font-semibold'>Indirect emissions from purchased energy accounted for <strong>{{$info['indirect_emissions']['tco2e']}}</strong> tCO2e.</x-renderer.heading>
                        </li>
                        <li>
                            <x-renderer.heading level=5 xalign='center' class='text-gray-600 font-semibold'>Other indirect emissions generated in the company's value chain were <strong>{{$info['other_indirect_emissions']['tco2e']}}</strong> tCO2e.</x-renderer.heading>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-full pb-4 absolute bottom-0 right-0 left-0 flex flex-row-reverse justify-center">
                <x-print.header6 :itemsShow="['website']"/>
            </div>
        </div>
        <x-renderer.page-break />
        {{-- CO2 Emission Summary Report --}}
        <div class="{{-- {{$layout}} --}}{{-- relative --}} w-[1400px] min-h-[990px] items-center bg-white box-border px-8 py-6 ">
            <x-renderer.heading level=3 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-2'>CO2 Emission Summary Report</x-renderer.heading>
            <div class="">
                @include('reports.document-ghg-summary-report-only-table')
                {{-- <div class="w-full absolute  pb-4 bottom-0 right-0 left-0 flex flex-row-reverse justify-center">
                    <x-print.header6 :itemsShow="['website']"/>
                </div> --}}
            </div>
        </div>
        
    </div>
</div>
@endsection
