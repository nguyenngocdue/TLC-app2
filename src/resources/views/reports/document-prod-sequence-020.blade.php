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
    {{-- @include('components.reports.show-layout2') --}}
</div>

@php
    $routeName = $routeName;
    $tableOfContents = $tableDataSource['table_of_contents'];
@endphp
<x-reports.table-of-contents-report routeName="$routeName" :dataSource="$tableOfContents"/>

{{-- RENDER TABLES --}}
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
                @foreach($tableDataSource['render_pages'] as $key => $data)
                    {{-- @dd($tableDataSource) --}}
                    @php
                        $dataTable = $data['tableDataSource'];
                        $prodRoutingLinkName = $dataTable->pluck('prod_routing_link_name')->first()->value;
                    @endphp
                        <div style='' class="{{-- {{$layout}} --}} w-[1400px] min-h-[1000px] items-center bg-white box-border p-8">
                                <x-print.header6 />
                                <x-renderer.heading level=2 class='text-center pt-10'>{{$prodRoutingLinkName}} Process Analysis Report</x-renderer.heading>
                                <x-renderer.heading level=3 class='text-center'>(Sequence-based Timesheet)</x-renderer.heading>
                                    <div class="grid grid-cols-12 ">
                                        <div class="col-span-12 text-left">
                                            <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">Basic Information <p class="text-sm font-light italic"></p>
                                            </h4>
                                        </div>
                                    </div>
                                {{-- BASIC INFORMATION --}}
                                    <div class="col-span-12 grid scroll-mt-80 snap-start border border-gray-600" id="page{{$key}}">
                                        <div class="grid grid-rows-1">
                                            <div class="grid grid-cols-12 text-right">
                                                <label class="{{$class1}} col-start- col-span-3">Project</label>
                                                <span class="{{$class2}}  col-start-4  col-span-4">{{$basicInfoData['project']}}</span>
                                                <label class="{{$class1}} col-start-8  col-span-3 items-center">Sub-Project</label>
                                                <span class="{{$class2}}  col-start-11 col-span-2">{{$basicInfoData['sub_project']}}</span>
                                            </div>
                                        </div>
                                        <div class="grid grid-rows-1">
                                            <div class="grid grid-cols-12 text-right ">
                                                <label class="{{$class1}} col-start-1 col-span-3">Production Routing</label>
                                                <span class="{{$class2}}  col-start-4 col-span-9">{{$basicInfoData['prod_routing']}}</span>
                                            </div>
                                        </div>
                                        @if(isset($basicInfoData['prod_discipline']))
                                        <div class="grid grid-rows-1">
                                            <div class="grid grid-cols-12 text-right ">
                                                <label class="{{$class1}} col-start-1 col-span-3">Production Discipline</label>
                                                <span class="{{$class2}}  col-start-4 col-span-9">{{$basicInfoData['prod_discipline']}}</span>
                                            </div>
                                        </div>
                                        @endif
                                        @if($prodRoutingLinkName)
                                        <div  class="grid grid-rows-1">
                                            <div class="grid grid-cols-12 text-right ">
                                                <label class="{{$class1}} col-start-1 col-span-3">Production Routing Link</label>
                                                <span  class="{{$class2}}  col-start-4 col-span-9">{{$prodRoutingLinkName}}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                {{-- TABLES --}}
                                    <div class="">
                                        <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300 pt-4" id="" title="" style="scroll-margin-top: 90px;">Detail Report</h4>
                                        <x-renderer.report.pivot-table 
                                                showNo={{true}} 
                                                :tableColumns="$tableColumns[$key]" 
                                                :dataSource="$dataTable" 
                                                :tableDataHeader="$tableDataHeader" 
                                                maxH='{{$maxH}}' 
                                                page-limit="{{$pageLimit}}" 
                                                tableTrueWidth={{$tableTrueWidth?1:0}}
                                                />
                                    </div>
                        </div>
                        <x-renderer.page-break />
                        {{-- Charts --}}
                        <div style='' class="{{-- {{$layout}} --}} w-[1400px] min-h-[1000px] items-center bg-white box-border p-8">
                            <div class="grid grid-cols-2 md:grid-cols-2 gap-2">
                                @foreach($data as $key => $items)
                                            @if(str_contains($key, 'widget'))
                                            {{-- @dd($items) --}}
                                                <div class="">
                                                    <x-renderer.heading level=5 xalign='left' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>
                                                        {{$prodRoutingLinkName}} {{$items['dimensions']['titleHeading']}} by Production Order
                                                    </x-renderer.heading>
                                                    {{-- @dump($items) --}}
                                                    <div class="p-6">
                                                            <x-renderer.report.chart-bar 
                                                                    key="{{md5($items['title_a'].$items['title_b'])}}" 
                                                                    chartType="{{$items['chart_type']}}" 
                                                                    :meta="$items['meta']" 
                                                                    :metric="$items['metric']" 
                                                                    :dimensions="$items['dimensions']"
                                                                    />
                                                    </div>
                                                </div>
                                            @endif
                                @endforeach
                            </div>
                        </div>
                        <x-renderer.page-break />
                @endforeach

</div>
@endsection
