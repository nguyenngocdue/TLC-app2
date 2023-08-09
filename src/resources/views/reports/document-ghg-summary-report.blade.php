@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $modeReport)
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
    {{-- <div class="md:px-4">
        <div style='page-break-after:always!important' class="w-[1000px] min-h-[1360px] items-center bg-white box-border p-8">
            <div class=" md:scroll-mt-20 sm:py-0 rounded-lg bg-white dark:border-gray-600">
                <div class="border-b p-0 font-medium flex items-center justify-left">
                    @php
                    @endphp
                    <div>
                        <div class="h-20 w-56">
                            <img alt="TLC Logo" src="/logo/tlc.png" class="h-full w-full">
                        </div>
                    </div>
                    <div class="w-[600px]">
                        <p class="text-lg font-normal text-center font-semibold">TLC Modular Construction LLC - July 2022 to December 2022 GHG emissions report</p>
                    </div>
                </div>
            </div>
        </div>
        <div style='page-break-after:always!important' class="w-[1000px] min-h-[1360px] items-center bg-white box-border p-8">
        </div> --}}
    {{-- <x-renderer.table showNo={{true}} :dataHeader="$tableDataHeader" :columns="$tableColumns" :dataSource="$tableDataSource" rotate45Width={{$rotate45Width}} maxH="{{$maxH}}" tableTrueWidth={{$tableTrueWidth?1:0}} /> --}}

    <div>
        <table border=1>
            <tr>
                <th>Category</th>
                <th>Emission source category</th>
                <th>t CO2e</th>
            </tr>
            <tr>
                <td rowspan="2">Direct emissions arising from owned or controlled stationary sources that use fossil fuels and/or emit fugitive emissions</td>
                <td>Fuels</td>
                <td>Value Of Fuels</td>
            </tr>
            <tr>
                <td>Refrigerants</td>
                <td>Value Of Fuels of Refrigerants</td>
            </tr>
        </table>
    </div>

</div>






@endsection
