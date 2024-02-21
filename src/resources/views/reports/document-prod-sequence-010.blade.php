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

<div class="px-4">
    @include('components.reports.shared-parameter')
    @include('components.reports.show-layout2', ['optionPrint' => $optionPrint])
</div>
{{-- RENDER TABLES --}}
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='page-break-after:always!important' class="{{$layout}} items-center bg-white box-border p-8">
            <x-print.header6 />
            {{-- BASIC INFORMATION --}}
            <x-renderer.heading level=2 class='text-center'>Target vs. Actual</x-renderer.heading>
            <x-renderer.heading level=3 class='text-center'>(Sequence-based Timesheet)</x-renderer.heading>
            <div class="grid grid-cols-12">
                <div class="col-span-12 text-left">
                    <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">Basic Information <p class="text-sm font-light italic"></p>
                    </h4>
                </div>
            </div>
            <div class="col-span-12 grid">
                <div class="grid grid-rows-1">
                    <div class="grid grid-cols-12 text-right ">
                        <label class="{{$class1}} col-start-1  col-span-3">Range Date</label>
                        <span class="{{$class2}}  col-start-4  col-span-9">from: 
                            <strong class='px-2'>"the beginning of project"</strong>
                            to:
                            <strong class='px-2'>{{$basicInfoData['date']}}</strong>
                        </span>
                    </div>
                </div>
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
        </div>
    </div>
</div>
@endsection
