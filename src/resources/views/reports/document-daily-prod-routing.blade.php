@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')

@php
$class1 = 'p-2 border h-full w-full flex border-gray-600 text-base font-medium bg-gray-50 items-center justify-end';
$class2 = 'p-2 border border-gray-600 flex justify-start items-center text-sm font-normal text-left'
@endphp


<div class="px-4">
    <div class="justify-end pb-5"></div>
    <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
        <x-reports.parameter3-report :itemsSelected="$modeParams" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    </div>
</div>
{{-- RENDER TABLES --}}
@foreach($dataRender as $key => $data)
@php
$tableColumns = $data['tableColumns'];
$tableDataSource = $data['tableDataSource'];
$basicInfo = $basicInfoData[$key];
@endphp
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='page-break-after:always!important' class="w-[1000px] min-h-[1360px] items-center bg-white box-border p-8">
            <x-print.header6 />

            {{-- BASIC INFORMATION --}}
            <x-renderer.heading level=2 class='text-center'>Production Routing Daily Report</x-renderer.heading>
            <div class="grid grid-cols-12">
                <div class="col-span-12 text-left">
                    <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">Basic Information <p class="text-sm font-light italic"></p>
                    </h4>
                </div>
            </div>
            <div class="col-span-12 grid">
                <div class="grid grid-rows-1">
                    <div class="grid grid-cols-12 text-right ">
                        <label class="{{$class1}} col-start-1  col-span-3">Date</label>
                        <span class="{{$class2}}  col-start-4  col-span-9">{{$basicInfo['date']}}</span>
                    </div>
                </div>
                <div class="grid grid-rows-1">
                    <div class="grid grid-cols-12 text-right">
                        <label class="{{$class1}} col-start-1  col-span-3">Project</label>
                        <span class="{{$class2}}  col-start-4  col-span-4">{{$basicInfo['project']}}</span>
                        <label class="{{$class1}} col-start-8  col-span-3 items-center">Sub-Project</label>
                        <span class="{{$class2}}  col-start-11 col-span-2">{{$basicInfo['sub_project']}}</span>
                    </div>
                </div>
                <div class="grid grid-rows-1">
                    <div class="grid grid-cols-12 text-right ">
                        <label class="{{$class1}} col-start-1   col-span-3">Production Routing</label>
                        <span class="{{$class2}}  col-start-4   col-span-9">{{$basicInfo['prod_routing']}}</span>
                    </div>
                </div>
                @if(isset($basicInfo['prod_discipline']) && $basicInfo['prod_discipline'])
                <div class="grid grid-rows-1">
                    <div class="grid grid-cols-12 text-right ">
                        <label class="{{$class1}} col-start-1   col-span-3">Production Discipline</label>
                        <span class="{{$class2}}  col-start-4   col-span-9">{{$basicInfo['prod_discipline']}}</span>
                    </div>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-12 items-center">
                <div class="col-span-12 text-left">
                    <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">{{$titleTables[$key]}}
                        <p class="text-sm font-light italic"></p>
                    </h4>
                </div>
            </div>
            <x-renderer.table showNo={{true}} :columns="$tableColumns" :dataSource="$tableDataSource" {{-- maxH="{{$maxH}}" --}} {{-- groupBy="{{$groupBy}}" groupByLength="{{$groupByLength}}" --}}/>
        </div>
    </div>
</div>
<x-renderer.page-break />
@endforeach
@endsection
