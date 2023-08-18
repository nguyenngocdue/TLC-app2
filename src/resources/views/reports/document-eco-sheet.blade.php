@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$mode)
@section('content')

<div class="px-4">
    <div class="justify-end pb-5"></div>
    <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
        <label for="" class="flex flex-1 text-gray-700 text-lg font-bold dark:text-white">Advanced Filter</label>
        <x-reports.parameter3-report :itemsSelected="$modeParams" modeOption="{{$currentMode}}" :columns="$paramColumns" routeName="{{$routeName}}" typeReport="{{$typeReport}}" entity="{{$entity}}" />
    </div>
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
                        <label class="p-2 border border-gray-600 text-base font-medium bg-gray-50 h-full w-full flex col-span-2 items-center justify-end col-start-1">Month</label>
                        <span class="p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-start-3 col-span-10 text-left">{{$basicInfoData['month']}}</span>
                    </div>
                </div>
                <div class="grid grid-rows-1">
                    <div class="grid grid-cols-12 text-right ">
                        <label class="p-2 border border-gray-600 text-base font-medium bg-gray-50 h-full w-full flex col-span-2 items-center justify-end col-start-1">Project</label>
                        <span class="p-2 border border-gray-600 flex justify-start items-center text-sm font-normal col-start-3 col-span-10 text-left">{{$basicInfoData['project_name']}}</span>
                    </div>
                </div>
            </div>


            {{-- RENDER TABLES --}}
            @foreach($dataSource as $key => $data)
            @php
            $tableColumns = $data['tableColumns'];
            $tableDataSource = $data['tableDataSource'];
            @endphp
            <div class="grid grid-cols-12 items-center">
                <div class="col-span-12 text-left">
                    <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">{{$titleTables[$key]}}
                        <p class="text-sm font-light italic"></p>
                    </h4>
                </div>
            </div>
            <x-renderer.table showNo={{true}} :columns="$tableColumns" :dataSource="$tableDataSource" />
            @endforeach
        </div>
    </div>
</div>
@endsection
