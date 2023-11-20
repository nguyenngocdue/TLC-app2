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
$classTextHeading = "text-[#1a401e] text-4xl font-roboto mt-[-100px] pl-20 text-bold font-semibold";

$titleColName = isset($params['quarter_time']) ? 'QTR'.implode(',',$params['quarter_time']) : 'YTD';
$titleColName = isset($params['only_month']) ? 'Total Quantity': $titleColName;
$year = $params['year'];
$data = $tableDataSource['carbon_footprint'][$year];
$pivotChart1 = $tableDataSource['pivot_chart_1'];
$pivotChart2 = $tableDataSource['pivot_chart_2'];
$info = $tableDataSource['info'];
$tableOfContents = $tableDataSource['table_of_contents'];

$text = isset($params['half_year']) ? ($params['half_year'] === 'start_half_year' ? 'Jan-Jun/'.$year : 'Jul-Dec/'.$year) 
                                        : $year;
$text = isset($params['only_month']) ? implode(',',App\Utils\Support\StringReport::stringsPad($params['only_month'])).'/'.$year: $text;

$text = isset($params['quarter_time']) && !isset($params['only_month']) 
                                    ? 'QTR'.implode(',',$params['quarter_time']).'/'.$text: $text;



@endphp
{{-- @dd($tableDataSource) --}}
<div class="px-4">
    @include('components.reports.shared-parameter')
    {{-- @include('components.reports.show-layout2') --}}
</div>
<x-reports.table-of-contents-report routeName="$routeName" :dataSource="$tableOfContents"/>
@php
        $layout = '';
        switch ($optionPrint) {
            case 'landscape':
            $layout = 'w-[1400px] min-h-[790px]';
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
        {{-- PAGE 1 --}}
        <div id="pageco2_emission_report"  style='page-break-after:always!important'  class="{{$layout}} flex m-auto items-center bg-white box-border relative">
                <img src="{{ asset('images/report/Green and white Sustainability modern presentation-1.png') }}" class="w-full h-full object-cover"/>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

        {{-- PAGE 2 --}}
        <div id="pagemethodology"  style='page-break-after:always!important' class="{{$layout}} flex m-auto items-center bg-white box-border relative overflow-hidden"> 
                <div class="z-10">
                    <x-print.header6 :itemsShow='["logo"]' dimensionImg="h-20 w-56" classImg="absolute top-0 right-0" class="border-none"/>
                    <x-renderer.heading level=1 xalign='left' class='absolute top-16 left-0 text-[#1a401e] text-4xl font-roboto  pl-16 text-bold font-semibold'>Methodology</x-renderer.heading>
                </div>
                <div class="absolute top-0 right-0 left-0  z-0">
                    <img src="{{ asset('images/report/Green and white Sustainability modern presentation-2.jpeg') }}" class="w-full h-full object-cover"/>
                </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

        {{-- PAGE 3 --}}
        
        <div id="pagecompany_carbon_footprint"  style='page-break-after:always!important'  class="{{$layout}} flex m-auto items-center bg-white box-border relative overflow-hidden"> 
                <div class="z-10">
                    <x-print.header6 :itemsShow='["logo"]' dimensionImg="h-20 w-56" classImg="" class="border-none absolute right-0 top-0"/>
                    <x-print.header6 :itemsShow='["website"]' class="border-none absolute bottom-0 left-0 right-0 justify-center mb-4"/>
                    <x-renderer.heading level=1 xalign='left' class='absolute top-16 left-0 text-[#1a401e] text-4xl font-roboto  pl-16 text-bold font-semibold'>Company's Carbon Footprint in {{$text}}</x-renderer.heading>
                </div>
            <div class='flex justify-center'>
                <div class="z-10 absolute left-[285px] top-[276px]">
                            <div class="flex  m-auto">
                                <div class="relative ">
                                    <div class='w-[370px] h-[313px] border-2  p-3 flex justify-between flex-col '>
                                    </div>
                                    <div class="absolute top-0 right-0 left-0 bottom-0">
                                        <div class="flex flex-col justify-between items-center h-full p-4">
                                            <h3 class='text-3xl font-roboto  text-white text-center'>Company Carbon Footprint</h3>
                                            <h4 class='text-6xl font-roboto  text-white'>{{$data['total_emission']}}</h4>
                                            <h2 class='text-lg text-white'>tCO2e</h2>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative ml-[90px]">
                                    <div class='w-[370px] h-[314px] border-2   flex justify-between flex-col '>
                                    </div>
                                    <div class="absolute top-0 right-0 left-0 bottom-0">
                                        <div class="flex flex-col justify-between items-center h-full p-4">
                                            <h3 class='text-3xl font-roboto  text-white text-center'>Carbon Footprint per Employee</h3>
                                            <h4 class='text-6xl font-roboto  text-white'>{{$data['co2_footprint_employee']}}</h4>
                                            <h2 class='text-lg text-white'>average tCO2e/FTE</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </div>
                <div class="pt-50"></div>
                
                <div class="absolute top-0 right-0 left-0  z-0">
                        <img src="{{ asset('images/report/Green and white Sustainability modern presentation-4.jpeg') }}" class="w-full h-full object-cover"/>
                </div>
            </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

        {{-- PAGE 4: Emission source category chart  --}}
        <div id="pageemission_category_chart"  style='page-break-after:always!important'  class="{{$layout}} flex m-auto items-center bg-white box-border p-8 relative"> 
            <div class="z-10">
                <x-print.header6 :itemsShow='["logo"]' dimensionImg="h-20 w-56" classImg="absolute top-0 right-0" class="border-none"/>
                <x-print.header6 :itemsShow='["website"]' class="border-none absolute bottom-0 left-0 right-0 justify-center mb-4"/>
                <x-renderer.heading level=1 xalign='left' class='absolute top-16 left-0 text-[#1a401e] text-4xl font-roboto  pl-16 text-bold font-semibold'>Emission Category Chart</x-renderer.heading>
            </div>
            <div class='flex justify-center m-auto'> 
                <div class="grid grid-rows-1">
                    <div class=" grid-rows-1 pt-10 flex justify-center flex-col items-center">
                        <div class="w-full flex px-4 z-10">
                            <div class="w-1/2 px-4">
                                <x-renderer.report.pivot-chart key="carbon_footprint_1" 
                                    :dataSource="$pivotChart1" 
                                    showValue={{true}} 
                                    showDataLabel={{false}}
                                    width="400"
                                    height="400"
                                ></x-renderer.report.pivot-chart>
                            </div>
                            <div class="m-auto">
                                @php
                                    $scopeNames = array_column($pivotChart1['scopeNames'], 'name');
                                @endphp
                                <x-renderer.card title="Carbon Emissions" tooltip="">
                                        @if(in_array('Scope 1', $scopeNames))
                                            <h5 class="text-[#1a401e] font-roboto mb-2 text-xl font-bold tracking-tight">Scope 1 (Direct Emission)</h5>
                                            <p class="text-[#1a401e] text-sm font-roboto font-normal">
                                                Emissions from sources that an organisation owns or controls directly.
                                            </p>
                                        @endif

                                        @if(in_array('Scope 2', $scopeNames))
                                            <h5 class="text-[#1a401e] font-roboto mb-2 text-xl font-bold tracking-tight">Scope 2 (Indirect Emission)</h5>
                                            <p class="text-[#1a401e] text-sm font-roboto font-normal">                                                    
                                                Emissions a company causes indirectly that come from where the energy it purchases and uses is produced.
                                            </p>
                                        @endif

                                        @if(in_array('Scope 3', $scopeNames))
                                            <h5 class="text-[#1a401e] font-roboto mb-2 text-xl font-bold tracking-tight">Scope 3 (Indirect Emission)</h5>
                                            <p class="text-[#1a401e] text-sm font-roboto font-normal">
                                                All emissions not covered in scope 1 or 2, created by a company's value chain.
                                            </p>
                                        @endif
                                </x-renderer.card>
                                {{-- Legen for chart 1 --}}
                                <div class="pt-2"></div>
                                <x-renderer.card title="Legend for Chart" tooltip="Legend for Chart">
                                        @php $class = '' @endphp
                                        @include('components.reports.under-legend-ghgco2-chart', ['class' => $class])
                                </x-renderer.card>
                            </div>
                        </div>
                    </div>
                    <div class="absolute top-0 right-0 left-0 opacity-20 z-0">
                        <img src="{{ asset('images/report/Green and white Sustainability modern presentation-3.jpeg') }}" class="w-full h-full object-cover"/>
                    </div>
                </div>
            </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

        {{-- PAGE 5: Emission source category chart --}}
        <div id="pageemission_source_category_chart"  style='page-break-after:always!important'  class="{{$layout}} flex m-auto items-center bg-white box-border p-8 relative"> 
            <div class="z-10">
                <x-print.header6 :itemsShow='["logo"]' dimensionImg="h-20 w-56" classImg="absolute top-0 right-0" class="border-none"/>
                <x-print.header6 :itemsShow='["website"]' class="border-none absolute bottom-0 left-0 right-0 justify-center mb-4"/>
                <x-renderer.heading level=1 xalign='left' class='absolute top-16 left-0 text-[#1a401e] text-4xl font-roboto  pl-16 text-bold font-semibold'>Emission Source Category Chart</x-renderer.heading>
            </div>
            <div class='flex justify-center m-auto'> 
                <div class="grid grid-rows-1 z-20">
                    <div class=" grid-rows-1 pt-2 flex justify-center flex-col items-center">
                        <div class="w-full px-4">
                            <div class="">
                                <x-renderer.report.pivot-chart 
                                        key="carbon_footprint_2" 
                                        :dataSource="$pivotChart2"
                                        width="1200"
                                        height="400"
                                    />
                            </div>
                            <div class="m-auto ">
                                {{-- Legen for chart 1 --}}
                                <div class="pt-2"></div>
                                <div class="">
                                    <x-renderer.card title="Legend for Chart" tooltip="Legend for Chart">
                                            @php 
                                                $class = 'hidden';
                                                $classUL = 'justify-between'; 
                                            @endphp
                                            @include('components.reports.under-legend-ghgco2-chart', ['class' => $class, 'classUL' => $classUL])
                                    </x-renderer.card>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="absolute top-0 right-0 left-0 opacity-20 z-0">
                    <img src="{{ asset('images/report/Green and white Sustainability modern presentation-3.jpeg') }}" class=" "/>
                </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

        
        {{-- PAGE 6 :Data Summary Report --}}
        <div  id="pagedata_summary_report"  style='page-break-after:always!important'  class=" w-[1400px] min-h-[790px] m-auto flex items-center bg-white box-border px-8 pb-8 relative">
            <div class="z-10">
                <x-print.header6 :itemsShow='["logo"]' dimensionImg="h-20 w-56" classImg="absolute top-0 right-0" class="border-none"/>
                <x-print.header6 :itemsShow='["website"]' class="border-none absolute bottom-0 left-0 right-0 justify-center mb-4"/>
                <x-renderer.heading level=1 xalign='left' class='absolute top-16 left-0 text-[#1a401e] text-4xl font-roboto  pl-16 text-bold font-semibold'>Data Summary Report</x-renderer.heading>
            </div>
            <div class='flex justify-center m-auto pt-40 pb-4'> 
                <div class=" border rounded-lg border-gray-300 dark:border-gray-600 overflow-hidden">
                    @include('reports.document-ghg-summary-report-only-table')
                </div>
            </div>
                <div class="absolute top-0 right-0 left-0 opacity-20 z-0">
                    {{-- <img src="{{ asset('images/report/Green and white Sustainability modern presentation-3.jpeg') }}" class="w-full h-full object-cover"/> --}}
                </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

         {{-- PAGE 7: GHGRP Basin Production & Emissions --}}
        <div id="pageghgrp_basin_production_emissions"  style='page-break-after:always!important'  class=" w-[1700px] min-h-[790px] flex m-auto items-center bg-white box-border relative pb-8"> 
            <div class="z-10">
                <x-print.header6 :itemsShow='["logo"]' dimensionImg="h-20 w-56" classImg="absolute top-0 right-0" class="border-none"/>
                <x-print.header6 :itemsShow='["website"]' class="border-none absolute bottom-0 left-0 right-0 justify-center mb-4"/>
                <x-renderer.heading level=1 xalign='left' class='absolute top-16 left-0 text-[#1a401e] text-4xl font-roboto  pl-16 text-bold font-semibold'>GHGRP Basin Production & Emissions</x-renderer.heading>
            </div>
            
            <div class='flex justify-center m-auto pt-28'> 
                    <div class="grid grid-rows-1 z-10 pt-10">
                            @php
                                $tableDataSource = $tableDataSource->toArray();
                            @endphp
                            <div class=" grid-rows-1 pt-2 flex justify-center flex-col items-center">
                                    @include('reports.include-document-ghg-sheet-040', ['tableDataSource' => $tableDataSource['document_ghg_sheet_040']])
                                <div class="bg-white border p-4 mt-2 break-normal min-w-0 dark:bg-gray-800 dark:border-gray-600 rounded shadow-xs">
                                    <ul class=" w-full flex  justify-between">
                                        <li class="flex items-center pb-2 pr-4 ">
                                            <div class="w-12 h-4 bg-[#4dc9f6]"></div>
                                            <p class='pl-4 text-gray-600 font-roboto  leading-tight text-xltext-center dark:text-gray-300'>
                                                The total CO2 emissions from the previous year.
                                            </p>
                                        </li>
                                        <li class="flex items-center pb-2 pr-4">
                                            <div class="w-12 h-4  bg-[#f67019]"></div>
                                            <p class='pl-4 text-gray-600 font-roboto  leading-tight text-xltext-center dark:text-gray-300'>
                                                The total CO2 emissions of the selected period.
                                            </p>
                                        </li>
                                        <li class="flex items-center pb-2">
                                            <div class="w-12 h-1 border rounded bg-[#6a329f]"></div>
                                            <p class='pl-4 text-gray-600 font-roboto  leading-tight text-xltext-center dark:text-gray-300'>
                                                Standard CO2 levels based on the previous year.
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="pb-4"></div>
                            </div>
                    </div>
                <div class="absolute top-0 right-0 left-0 opacity-20 z-0">
                    <img src="{{ asset('images/report/Green and white Sustainability modern presentation-3.jpeg') }}" class=" object-cover"/>
                </div>
            </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />


        {{-- PAGE 8: Data Detail Report  --}}
        <div id="pagedata_detail_report"  style='page-break-after:always!important'  class=" w-min-[1400px] min-h-[790px] flex items-center bg-white box-border relative px-8">
            <div class="z-5">
                <x-print.header6 :itemsShow='["logo"]' dimensionImg="h-20 w-56" classImg="absolute top-0 right-0" class="border-none"/>
                <x-print.header6 :itemsShow='["website"]' class="border-none absolute bottom-0 left-0 right-0 justify-center mb-4"/>
                <x-renderer.heading level=1 xalign='left' class='absolute top-16 left-0 text-[#1a401e] text-4xl font-roboto  pl-16 text-bold font-semibold'>Data Detail Report</x-renderer.heading>
            </div>
            <div class='flex justify-center m-auto pt-20 pb-8'> 
                <div class="pt-20 z-5">
                    <div class="grid grid-rows-1">
                        @include('reports.include-document-ghg-sheet-050', ['tableDataSource' => $tableDataSource['document_ghg_sheet_050']])
                    </div>
                </div>
                <div class="absolute top-0 right-0 left-0 opacity-20 z-0 hidden">
                    <img src="{{ asset('images/report/Green and white Sustainability modern presentation-3.jpeg') }}" class="w-full h-full object-cover"/>
                </div>
            </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

        {{-- PAGE 9: Data Detail Report on Metric 2 --}}
        <div id="pagedata_detail_report_on_metric2"  style='page-break-after:always!important'  class=" w-min-[1400px] min-h-[790px] flex items-center bg-white box-border relative px-8">
            <div class="z-5">
                <x-print.header6 :itemsShow='["logo"]' dimensionImg="h-20 w-56" classImg="absolute top-0 right-0" class="border-none"/>
                <x-print.header6 :itemsShow='["website"]' class="border-none absolute bottom-0 left-0 right-0 justify-center mb-4"/>
                <x-renderer.heading level=1 xalign='left' class='absolute top-16 left-0 text-[#1a401e] text-4xl font-roboto  pl-16 text-bold font-semibold'>Data Detail Report</x-renderer.heading>
            </div>
            <div class='flex justify-center m-auto pt-20 pb-8'> 
                <div class="pt-20 z-5">
                    <div class="grid grid-rows-1">
                            @include('reports.include-document-ghg-sheet-060', ['tableDataSource' => $tableDataSource['document_ghg_sheet_060']])
                    </div>
                </div>
                <div class="absolute top-0 right-0 left-0 opacity-20 z-0 hidden">
                    <img src="{{ asset('images/report/Green and white Sustainability modern presentation-3.jpeg') }}" class="w-full h-full object-cover"/>
                </div>
            </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

    </div>
</div>
@endsection