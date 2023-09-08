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
    @include('components.reports.shared-parameter')
</div>
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='page-break-after:always!important' class="w-[1000px] min-h-[1360px] items-center bg-white box-border p-8">
            <x-print.header6 />
            {{-- BASIC INFORMATION --}}
            <div class="grid grid-cols-12">
                <div class="col-span-12 text-left">
                    <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">Basic Information <p class="text-sm font-light italic"></p>
                    </h4>
                </div>
            </div>
            <div class="col-span-12 grid">
                <div class="grid grid-rows-1">
                    <div class="grid grid-cols-12 text-right ">
                        <label class="{{$class1}} col-start-1  col-span-3">Month</label>
                        <span class="{{$class2}}  col-start-4  col-span-9">{{$basicInfoData['month']}}</span>
                    </div>
                </div>
                <div class="grid grid-rows-1">
                    <div class="grid grid-cols-12 text-right ">
                        <label class="{{$class1}} col-start-1  col-span-3">Project</label>
                        <span class="{{$class2}}  col-start-4  col-span-9">{{$basicInfoData['project_name']}}</span>
                    </div>
                </div>
            </div>
            {{-- RENDER TABLES --}}
            <div>
                <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">Labor Impacts</h4>
                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['ecoLaborImpacts']" :dataSource="$tableDataSource['ecoLaborImpacts']" />
            </div>
            <div>
                <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">Material Add</h4>
                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['ecoSheetsMaterialAdd']" :dataSource="$tableDataSource['ecoSheetsMaterialAdd']" />
            </div>
            <div>
                <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">Material Remove</h4>
                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['ecoSheetsMaterialRemove']" :dataSource="$tableDataSource['ecoSheetsMaterialRemove']" />
            </div>
            <div>
                <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">Sign Off</h4>
                <x-renderer.report.pivot-table showNo={{true}} :tableColumns="$tableColumns['timeEcoSheetSignOff']" :dataSource="$tableDataSource['timeEcoSheetSignOff']" />
            </div>

        </div>


    </div>
</div>
@endsection
