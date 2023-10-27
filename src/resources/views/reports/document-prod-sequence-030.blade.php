@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')

@php
$class1 = 'p-2 border h-full w-full flex border-gray-600 text-base font-medium bg-gray-50 items-center justify-end';
$class2 = 'p-2 border border-gray-600 flex justify-start items-center text-sm font-normal text-left';
$tableData = isset($tableDataSource['tableDataSource']) ? collect($tableDataSource['tableDataSource']) : [];
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
{{-- @dd($tableDataSource, $basicInfoData) --}}
{{-- RENDER TABLES --}}
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        @foreach($tableDataSource as $key => $widget)
            @if(str_contains($key, 'widget'))
                <div style='page-break-after:always!important' class="{{$layout}} items-center bg-white box-border p-8">
                    <x-print.header6 />
                    <x-renderer.heading level=3 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>Work Completion Percentage Report Chart</x-renderer.heading>
                        @php
                            $basicInfo = $basicInfoData;
                        @endphp
                        <div class="col-span-12 grid border border-gray-600">
                            <div class="grid grid-rows-1">
                                <div class="grid grid-cols-12 text-right">
                                    <label class="{{$class1}} col-start-1  col-span-3">Project</label>
                                    <span class="{{$class2}}  col-start-4  col-span-4">{{$basicInfoData['project_name']}}</span>
                                    <label class="{{$class1}} col-start-8  col-span-3 items-center">Sub-Project</label>
                                    <span class="{{$class2}}  col-start-11 col-span-2">{{$basicInfoData['sub_project_name']}}</span>
                                </div>
                            </div>
                            <div class="grid grid-rows-1">
                                <div class="grid grid-cols-12 text-right ">
                                    <label class="{{$class1}} col-start-1   col-span-3">Production Routing</label>
                                    <span class="{{$class2}}  col-start-4   col-span-9">{{$basicInfoData['prod_routing_name']}}</span>
                                </div>
                            </div>
                            <div class="grid grid-rows-1">
                                <div class="grid grid-cols-12 text-right ">
                                    <label class="{{$class1}} col-start-1   col-span-3">Production Discipline</label>
                                    <span class="{{$class2}}  col-start-4   col-span-9">{{$basicInfoData['prod_discipline_name']}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-10">
                        {{-- @dump($widget) --}}
                                <x-renderer.report.chart-bar 
                                        key="{{md5($widget['title_a'].$widget['title_b'])}}" 
                                        chartType="{{$widget['chart_type']}}" 
                                        :meta="$widget['meta']" 
                                        :metric="$widget['metric']" 
                                        :dimensions="$widget['dimensions']"
                                        />
                                <x-renderer.heading level=5 xalign='center' class='text-gray-600 font-semibold p-4'>The chart represents the completion percentage of each step in the production routing link on every production order.</x-renderer.heading>
                        </div>
                </div>
            @endif
        <x-renderer.page-break />
        @endforeach
        
        <div style='page-break-after:always!important' class="{{$layout}} items-center bg-white box-border p-8">
            <x-print.header6 />
            
              {{-- BASIC INFORMATION --}}
            <x-renderer.heading level=2 class='text-center'>Work Completion Percentage Report</x-renderer.heading>
            <x-renderer.heading level=3 class='text-center'>(Sequence-based Timesheet)</x-renderer.heading>
            <div class="grid grid-cols-12">
                <div class="col-span-12 text-left">
                    <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">Basic Information <p class="text-sm font-light italic"></p>
                    </h4>
                </div>
            </div>
             <div class="col-span-12 grid border border-gray-600">
                <div class="grid grid-rows-1">
                    <div class="grid grid-cols-12 text-right">
                        <label class="{{$class1}} col-start-1  col-span-3">Project</label>
                        <span class="{{$class2}}  col-start-4  col-span-4">{{$basicInfoData['project_name']}}</span>
                        <label class="{{$class1}} col-start-8  col-span-3 items-center">Sub-Project</label>
                        <span class="{{$class2}}  col-start-11 col-span-2">{{$basicInfoData['sub_project_name']}}</span>
                    </div>
                </div>
                <div class="grid grid-rows-1">
                    <div class="grid grid-cols-12 text-right ">
                        <label class="{{$class1}} col-start-1   col-span-3">Production Routing</label>
                        <span class="{{$class2}}  col-start-4   col-span-9">{{$basicInfoData['prod_routing_name']}}</span>
                    </div>
                </div>
                @if(isset($basicInfoData['prod_discipline_name']) && $basicInfoData['prod_discipline_name'])
                <div class="grid grid-rows-1">
                    <div class="grid grid-cols-12 text-right ">
                        <label class="{{$class1}} col-start-1   col-span-3">Production Discipline</label>
                        <span class="{{$class2}}  col-start-4   col-span-9">{{$basicInfoData['prod_discipline_name']}}</span>
                    </div>
                </div>
                @endif
            </div>
           
            {{-- TABLES --}}
            <div class="">
                <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">Detail Report</h4>
                <x-renderer.report.pivot-table 
                        showNo={{true}} 
                        :tableColumns="$tableColumns" 
                        :dataSource="$tableData" 
                        :tableDataHeader="$tableDataHeader" 
                        maxH='{{$maxH}}' 
                        page-limit="{{$pageLimit}}" 
                        tableTrueWidth={{$tableTrueWidth?1:0}}
                        />
            </div>

        </div>
    </div>
</div>
@endsection
