@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')

@php
$class1 = 'p-2 border h-full w-full flex border-gray-600 text-base font-medium bg-gray-50 items-center justify-end';
$class2 = 'p-2 border border-gray-600 flex justify-start items-center text-sm font-normal text-left'
@endphp

{{-- "Printing settings"  --}}
@php
    switch ($valueOptionPrint) {
        case 'landscape':
        $layout = 'w-[1400px] min-h-[1000px]';
        break;
        case 'portrait':
        default:
            $layout = 'w-[1000px] min-h-[1360px]';
            break;
    }
@endphp


<div class="px-4">
    @include('components.reports.shared-parameter')
    @include('components.reports.show-layout')
</div>

{{-- RENDER WHEN THERE ARE NO ITEMS --}}
@if($isEmptyAllDataSource)
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='page-break-after:always!important' class="{{$layout}} items-center bg-white box-border p-8">
            <div class="p-10 text-center text-gray-500 ">
                There is no item to be found from "Advanced Filter"
            </div>
        </div>
    </div>
</div>
@elseif($emptyItems)
<div class="grid grid-row-1 w-full md:px-4 pb-4">
    <x-renderer.report.list-empty-items :dataSource="$emptyItems" title="The dates with data are empty." span='1' />
</div>
@endif

{{-- @dd($tableDataSource) --}}
{{-- RENDER WHEN THERE ARE ITEMS --}}
@foreach($tableDataSource as $key => $data)
@php
$basicInfo = $basicInfoData[$key];
@endphp
@if($data->toArray())
{{-- RENDER TABLES --}}
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='page-break-after:always!important' class="{{$layout}} items-center bg-white box-border p-8">
            <x-print.header6 />
            {{-- BASIC INFORMATION --}}
            <x-renderer.heading level=2 class='text-center'>{{$titleReport}}</x-renderer.heading>
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

            <div class="">
                <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">Detail Report</h4>
                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns" :dataSource="$data" />
            </div>
        </div>
    </div>
</div>
<x-renderer.page-break />
@endif
@endforeach
@endsection
