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
    //dd($tableDataSource);
@endphp

<div class="px-4">
    @include('components.reports.shared-parameter')
    {{-- @include('components.reports.show-layout2') --}}
</div>


<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
                {{-- RENDER TABLE --}}
                @if(isset($tableDataSource['tableDataSource']))
                        @php
                            $dataTable = $tableDataSource['tableDataSource'];
                            $workplace = $dataTable->pluck('workplace_name')->first();
                        @endphp
                            <div style='' class="{{-- {{$layout}} --}} w-[1400px] min-h-[1000px] items-center bg-white box-border p-8">
                                    <x-print.header6 />
                                    <x-renderer.heading level=2 class='text-center pt-10'>Calculate Working Hours for Each Workplace</x-renderer.heading>
                                    {{-- TABLES --}}
                                        <div class="">
                                            <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300 pt-4" id="" title="" style="scroll-margin-top: 90px;">Detail Report</h4>
                                            <x-renderer.report.pivot-table 
                                                    showNo={{true}} 
                                                    :tableColumns="$tableColumns" 
                                                    :dataSource="$dataTable" 
                                                    :tableDataHeader="$tableDataHeader" 
                                                    maxH='{{$maxH}}' 
                                                    page-limit="{{$pageLimit}}" 
                                                    tableTrueWidth={{$tableTrueWidth?1:0}}
                                                    />
                                        </div>
                            </div>
                @endif
                
                {{-- RENDER CHART --}}
                @if(isset($tableDataSource['render_pages']))
                    @php
                        $charts = $tableDataSource['render_pages'];
                        $charts = array_chunk($charts,4);
                    @endphp
                        @foreach($charts as $k1 => $items)
                            <div style='' class="w-[1400px] min-h-[990px] items-center bg-white box-border p-8">
                                <div class="pt-5">
                                    <x-print.header6 />
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($items as $k2 => $item)
                                                @php
                                                    $values = $item['dataWidgets']['percent_downtime_each_workplace'];
                                                @endphp
                                                    <div>
                                                        <x-renderer.heading level=5 xalign='left' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>Workplace:<strong>{{$values['key_of_data_source']}} </strong></x-renderer.heading>
                                                        <div class="p-6">
                                                            <x-renderer.report.pivot-chart4 :data="$values"/>
                                                        </div>
                                                    </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <x-renderer.page-break />
                        @endforeach
                {{-- RENDER WHEN THERE ARE NO ITEMS --}}
                @else
                    <x-reports.empty-data-report layout="{{$layout}}" />
                @endif

</div>
@endsection
