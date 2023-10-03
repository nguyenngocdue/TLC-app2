@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')

@php
$class1 = 'p-2 border h-full w-full flex border-gray-600 text-base font-medium bg-gray-50 items-center justify-end';
$class2 = 'p-2 border border-gray-600 flex justify-start items-center text-sm font-normal text-left';
$widget = $tableDataSource['widget_01'];
$tableDataSource = isset($tableDataSource['tableDataSource']) ? collect($tableDataSource['tableDataSource']) : [];
@endphp
{{-- "Show utility"  --}}
@php
    $tl = "<div></div>";
    $tc = "<x-reports.utility-report routeName='$routeName'/>"; 
    $tr = "<x-reports.per-page-report typeReport='$typeReport' entity='$entity' routeName='$routeName' page-limit='$pageLimit' formName='updatePerPage' />"; 
@endphp

<div class="px-4">
    @include('components.reports.shared-parameter')
    @include('components.reports.show-layout', ['optionPrint' => $optionPrint])
</div>


{{-- RENDER TABLES --}}
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='page-break-after:always!important' class="{{$layout}} items-center bg-white box-border p-8">
                {{-- Widget --}}
            <x-print.header6 />
            {{-- BASIC INFORMATION --}}
            <x-renderer.heading level=2 class='text-center'>Report on Production Routing Links by Day</x-renderer.heading>
            <x-renderer.heading level=3 class='text-center'>(Sequence-based Timesheet)</x-renderer.heading>
            <div class="grid grid-cols-12">
                <div class="col-span-12 text-left">
                    <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">Basic Information <p class="text-sm font-light italic"></p>
                    </h4>
                </div>
            </div>
            <div class="col-span-12 grid">
                <div class="grid grid-rows-1">
                    <div class="grid grid-cols-12 text-right">
                        <label class="{{$class1}} col-start-1  col-span-3">Project</label>
                        <span class="{{$class2}}  col-start-4  col-span-4">{{$basicInfoData['project']}}</span>
                        <label class="{{$class1}} col-start-8  col-span-3 items-center">Sub-Project</label>
                        <span class="{{$class2}}  col-start-11 col-span-2">{{$basicInfoData['sub_project']}}</span>
                    </div>
                </div>
                <div class="grid grid-rows-1">
                    <div class="grid grid-cols-12 text-right ">
                        <label class="{{$class1}} col-start-1   col-span-3">Production Routing</label>
                        <span class="{{$class2}}  col-start-4   col-span-9">{{$basicInfoData['prod_routing']}}</span>
                    </div>
                </div>
                @if(isset($basicInfoData['prod_discipline']) && $basicInfoData['prod_discipline'])
                <div class="grid grid-rows-1">
                    <div class="grid grid-cols-12 text-right ">
                        <label class="{{$class1}} col-start-1   col-span-3">Production Discipline</label>
                        <span class="{{$class2}}  col-start-4   col-span-9">{{$basicInfoData['prod_discipline']}}</span>
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
                        :dataSource="$tableDataSource" 
                        :tableDataHeader="$tableDataHeader" 
                        maxH='{{$maxH}}' 
                        page-limit="{{$pageLimit}}" 
                        tableTrueWidth={{$tableTrueWidth?1:0}}

                        {{-- showPaginationTop="true" --}}
                        {{-- topRightControl="{!!$tr!!}" 
                        topCenterControl="{!!$tc!!}"  --}}
                        />
            </div>

                <x-renderer.heading level=3 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>Chart displaying job completion time statistics by day</x-renderer.heading>
               <div class="p-10">
                    <x-renderer.report.chart-bar 
                                                key="{{md5($widget['title_a'].$widget['title_b'])}}" 
                                                chartType="{{$widget['chartType']}}" 
                                                :meta="$widget['meta']" 
                                                :metric="$widget['metric']" 
                                                titleChart="{{$widget['titleChart']}}" 
                                                :dimensions="$widget['dimensions']"
                                                />
                    <x-renderer.heading level=5 xalign='center' class='text-gray-600 font-semibold p-4'>The chart represents the completion percentage of each step in the production routing link on every production order.</x-renderer.heading>
                </div>
        </div>
        </div>
    </div>
</div>
@endsection
