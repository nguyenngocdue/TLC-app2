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

$titleColName = isset($params['quarter_time']) ? 'QTR'.implode(',',$params['quarter_time']) : 'YTD';
$titleColName = isset($params['only_month']) ? 'Total Quantity': $titleColName;
$year = $params['year'];
$data = $tableDataSource['carbon_footprint'][$year];
$pivotChart1 = $tableDataSource['pivot_chart_1'];
$pivotChart2 = $tableDataSource['pivot_chart_2'];
$info = $tableDataSource['info'];

$text = isset($params['half_year']) ? ($params['half_year'] === 'start_half_year' ? 'Jan-Jun/'.$year : 'Jul-Dec/'.$year) 
                                        : $year;
$text = isset($params['only_month']) ? implode(',',App\Utils\Support\StringReport::stringsPad($params['only_month'])).'/'.$year: $text;

$text = isset($params['quarter_time']) && !isset($params['only_month']) 
                                    ? 'QTR'.implode(',',$params['quarter_time']).'/'.$text: $text;



@endphp
{{-- @dump($tableDataSource) --}}

<div class="px-4">
    @include('components.reports.shared-parameter')
    @include('components.reports.show-layout2')
</div>
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
        <div class="{{$layout}} flex m-auto items-center bg-white box-border p-8 relative"> 
            <div class='flex justify-center'> 
                <img src="{{ asset('images/report/Green and white Sustainability modern presentation-1.png') }}" class="w-full h-full object-cover"/>
            </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

        {{-- PAGE 2 --}}
        <div class="{{$layout}} flex m-auto items-center bg-white box-border p-8 relative"> 
            <div class='flex justify-center'> 
                <img src="{{ asset('images/report/Green and white Sustainability modern presentation-2.png') }}" class="w-full h-full object-cover"/>
            </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

        {{-- PAGE 3 --}}
        <div class="{{$layout}} flex m-auto items-center bg-white box-border p-8 relative"> 
            <div class='flex justify-center'> 
                <img src="{{ asset('images/report/Green and white Sustainability modern presentation-3.png') }}" class="w-full h-full object-cover"/>
                <div class="absolute top-16">
                   <x-renderer.heading level=2 xalign='center' class='text-[#1a401e] text-4xl font-roboto font-bold p-2'>Company's carbon footprint in {{$text}}</x-renderer.heading>
                    <div class="grid grid-rows-1 pt-20">
                        <div class="grid grid-cols-12 gap-28 text-center">
                            <div class="col-span-6 m-auto  relative">
                                <div class='w-96 h-96 border-2 opacity-80 bg-green-700 p-3 flex justify-between flex-col '>
                                </div>
                                <div class="absolute top-0 right-0 left-0 bottom-0">
                                    <div class="flex flex-col justify-between h-full p-4">
                                        <h3 class='text-3xl font-roboto  text-white'>Company Carbon Footprint</h3>
                                        <h4 class='text-6xl font-roboto  text-white'>{{$data['total_emission']}}</h4>
                                        <h2 class='text-lg text-white'>tCO2e</h2>
                                    </div>
                                </div>
                            </div>
                             <div class="col-span-6 m-auto  relative">
                                <div class='w-96 h-96 border-2 opacity-80 bg-violet-600 flex justify-between flex-col '>
                                </div>
                                <div class="absolute top-0 right-0 left-0 bottom-0">
                                    <div class="flex flex-col justify-between h-full p-4">
                                        <h3 class='text-3xl font-roboto  text-white'>Carbon Footprint per Employee</h3>
                                        <h4 class='text-6xl font-roboto  text-white'>{{$data['co2_footprint_employee']}}</h4>
                                        <h2 class='text-lg text-white'>average tCO2e/FTE</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

        {{-- PAGE 4: Emission source category chart  --}}
        <div class="{{$layout}} flex m-auto items-center bg-white box-border p-8 relative"> 
            <div class='flex justify-center'> 
                <div class="grid grid-rows-1">
                    <x-renderer.heading level=3 xalign='center' class='text-[#1a401e] text-4xl font-roboto font-bold p-2'>Emission source category chart</x-renderer.heading>
                    <div class=" grid-rows-1 pt-2 flex justify-center flex-col items-center">
                        <div class="w-full flex px-4 z-10">
                            <div class="w-1/2 px-4">
                                <x-renderer.report.pivot-chart key="carbon_footprint_1" 
                                    :dataSource="$pivotChart1" 
                                    showValue={{true}} 
                                    showDataLabel={{false}}
                                    width="600"
                                    height="500"
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
                    <div class="absolute top-0 opacity-30 z-0">
                        <img src="{{ asset('images/report/Green and white Sustainability modern presentation-3.png') }}" class="w-full h-full object-cover"/>
                    </div>
                </div>
            </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

        {{-- PAGE 5: Emission source category chart --}}
        <div class="{{$layout}} flex m-auto items-center bg-white box-border p-8 relative"> 
            <div class='flex justify-center'> 
                <div class="grid grid-rows-1 z-20">
                    <x-renderer.heading level=3 xalign='center' class='text-[#1a401e] text-4xl font-roboto font-bold p-2'>Emission source category chart</x-renderer.heading>
                    <div class=" grid-rows-1 pt-2 flex justify-center flex-col items-center">
                        <div class="w-full px-4">
                            <div class="">
                                <x-renderer.report.pivot-chart 
                                        key="carbon_footprint_2" 
                                        :dataSource="$pivotChart2"
                                        width="1200"
                                        height="500"
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
                <div class="absolute top-0 opacity-20 z-10">
                    <img src="{{ asset('images/report/Green and white Sustainability modern presentation-3.png') }}" class=" "/>
                </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

        
        {{-- PAGE 6 :Data Summary Report --}}
        <div class=" w-[1400px] min-h-[940px] m-auto flex items-center bg-white box-border p-8 relative"> 
            <div class='flex justify-center'> 
                    <div class="grid grid-rows-1 z-10 pt-10">
                        <x-renderer.heading level=3 xalign='center' class='text-[#1a401e] text-4xl font-roboto font-bold p-2'>GHGRP Basin Production & Emissions</x-renderer.heading>
                            <div class="border rounded-lg border-gray-300 dark:border-gray-600 overflow-hidden">
                                @include('reports.document-ghg-summary-report-only-table')
                            </div>
                    </div>
                <div class="absolute top-0 right-0 left-0 opacity-30 z-0">
                    <img src="{{ asset('images/report/Green and white Sustainability modern presentation-3.png') }}" class="w-full h-full object-cover"/>
                </div>
            </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

         {{-- PAGE 7: GHGRP Basin Production & Emissions --}}
        <div class=" w-min-[1400px] min-h-[940px] flex items-center bg-white box-border p-8 relative"> 
            <div class='flex justify-center m-auto'> 
                    <div class="grid grid-rows-1 z-10 pt-10">
                        <x-renderer.heading level=3 xalign='center' class='text-[#1a401e] text-4xl font-roboto font-bold p-2'>GHGRP Basin Production & Emissions</x-renderer.heading>
                            @php
                                $tableDataSource = $tableDataSource->toArray();
                            @endphp
                            <div class=" grid-rows-1 pt-2 flex justify-center flex-col items-center">
                                @include('reports.include-document-ghg-sheet-040', ['tableDataSource' => $tableDataSource['document_ghg_sheet_040']])
                                <div class="bg-white border p-4 mt-2 break-normal min-w-0 dark:bg-gray-800 dark:border-gray-600 rounded shadow-xs">
                                    <ul class=" flex flex-col items-start">
                                        <li class="flex items-center pb-2">
                                            <div class="w-12 h-4 bg-[#4dc9f6]"></div>
                                            <p class='pl-4 text-gray-600 font-roboto  leading-tight text-xltext-center dark:text-gray-300'>
                                                The amount of CO2 emissions based on the results for the entire year.
                                            </p>
                                        </li>
                                        <li class="flex items-center pb-2">
                                            <div class="w-12 h-4  bg-[#f67019]"></div>
                                            <p class='pl-4 text-gray-600 font-roboto  leading-tight text-xltext-center dark:text-gray-300'>
                                                The amount of CO2 up to the selected moment.
                                            </p>
                                        </li>
                                        <li class="flex items-center pb-2">
                                            <div class="w-12 h-4  bg-[#6a329f]"></div>
                                            <p class='pl-4 text-gray-600 font-roboto  leading-tight text-xltext-center dark:text-gray-300'>
                                                Standard CO2 levels.
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                    </div>
                <div class="absolute top-0 right-0 left-0 opacity-30 z-0">
                    <img src="{{ asset('images/report/Green and white Sustainability modern presentation-3.png') }}" class="w-full h-full object-cover"/>
                </div>
            </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />


        {{-- PAGE 8: GHGRP Basin Production & Emissions --}}
        <div class=" w-min-[1400px] flex items-center bg-white box-border p-8 "> 
            <div class='flex justify-center relative'> 
                <div class="z-2 pt-20">
                    <div class="grid grid-rows-1">
                        <x-renderer.heading level=3 xalign='center' class='text-[#1a401e] text-4xl font-roboto font-bold p-2'>GHGRP Basin Production & Emissions</x-renderer.heading>
                            <div class=" grid-rows-1 pt-2 flex justify-center flex-col items-center">
                                @include('reports.include-document-ghg-sheet-050', ['tableDataSource' => $tableDataSource['document_ghg_sheet_050']])
                            </div>
                    </div>
                </div>
                <div class="absolute top-0 right-0 left-0 opacity-30">
                    <img src="{{ asset('images/report/Green and white Sustainability modern presentation-3.png') }}" class="w-full h-full object-cover"/>
                </div>
            </div>
        </div>
        {{-- END --}}
        <x-renderer.page-break />

    </div>
</div>
@endsection