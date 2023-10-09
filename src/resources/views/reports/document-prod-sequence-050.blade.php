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
    @include('components.reports.show-layout2')
</div>


{{-- RENDER TABLES --}}
<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
                <div style='page-break-after:always!important' class=" items-center  box-border p-8">
                    {{-- TABLES --}}    
                    @php
                        $tableOfContents = $tableDataSource['table_of_contents'];
                        $n = 0;
                    @endphp
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            No .
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Production Routing Link
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tableOfContents as $key => $value)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{++$n}}
                                            </th>
                                            <td class="px-6 py-4">
                                                <a href="#sheet{{$key}}" class="text-blue-600">{{$value}}</a>
                                            </td>
                                        </tr>

                                    @endforeach                                    
                                </tbody>
                            </table>
                        </div>


                </div>


                @foreach($tableDataSource['render_pages'] as $key => $data)
                    @php
                        $dataTable = $data['tableDataSource'];
                        $prodRoutingLinkName = $dataTable->pluck('prod_routing_link_name')->first()->value;
                    @endphp
                        <div style='page-break-after:always!important' class="{{$layout}} items-center bg-white box-border p-8">
                                <x-print.header6 />
                                {{-- BASIC INFORMATION --}}
                                <x-renderer.heading level=2 class='text-center'>Workload Report Across Each Production Order</x-renderer.heading>
                                <x-renderer.heading level=3 class='text-center'>(Sequence-based Timesheet)</x-renderer.heading>
                                    <div class="grid grid-cols-12">
                                        <div class="col-span-12 text-left">
                                            <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">Basic Information <p class="text-sm font-light italic"></p>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-span-12 grid scroll-mt-80 snap-start" id="sheet{{$key}}">
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
                                        <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300" id="" title="" style="scroll-margin-top: 90px;">Detail Report</h4>
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
                        <x-renderer.page-break />
                        @foreach($data as $key => $items)
                                    @if(str_contains($key, 'widget'))
                                        <div style='page-break-after:always!important' class="{{$layout}} items-center bg-white box-border p-8">
                                            <x-renderer.heading level=3 xalign='center' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>
                                                Comparing <strong>{{$items['dimensions']['titleChart']}} </strong>Across Production Orders
                                            </x-renderer.heading>
                                            {{-- @dump($items) --}}
                                            <div class="p-10">
                                                    <x-renderer.report.chart-bar 
                                                            key="{{md5($items['title_a'].$items['title_b'])}}" 
                                                            chartType="{{$items['chartType']}}" 
                                                            :meta="$items['meta']" 
                                                            :metric="$items['metric']" 
                                                            :dimensions="$items['dimensions']"
                                                            />
                                                    <x-renderer.heading level=5 xalign='center' class='text-gray-600 font-semibold p-4'>
                                                                        The chart compares the 
                                                                        <strong>{{$items['dimensions']['titleChart']}}</strong> 
                                                                        values for each Production Order according to the <strong> Production Routing Link</strong>.
                                                    </x-renderer.heading>
                                            </div>
                                        </div>
                                    @endif
                                <x-renderer.page-break />
                        @endforeach
                @endforeach
</div>
@endsection
