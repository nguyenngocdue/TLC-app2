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
{{-- @dump($tableDataSource['dataWidgets']) --}}
{{-- RENDER TABLES --}}
<div class="flex justify-center bg-only-print">
    <div class="md:px-4"> 
        <div style='' class="{{$layout}} items-center bg-white box-border p-8">
                <div class="pt-5">
                    <x-print.header6 />
                    <x-renderer.heading level=2 xalign='center' class='text-center pt-4'>Benchmark Report</x-renderer.heading>
                    <x-renderer.heading level=3 xalign='center'  class='text-center'>(for Production Routing Link)</x-renderer.heading>
                        <div class="col-span-12 grid border border-gray-600">
                            <div class="grid grid-rows-1">
                                <div class="grid grid-cols-12 text-right ">
                                    <label class="{{$class1}} col-start-1   col-span-3">Sub-Project</label>
                                    <span class="{{$class2}}  col-start-4   col-span-9">{{$basicInfoData['sub_project_name']}}</span>
                                </div>
                            </div>
                            <div class="grid grid-rows-1">
                                    @if($basicInfoData['prod_discipline_name'])
                                        <div class="grid grid-cols-12 text-right">
                                            <label class="{{$class1}} col-start-1  col-span-3">Production Routing</label>
                                            <span class="{{$class2}}  col-start-4  col-span-4">{{$basicInfoData['prod_routing_name']}}</span>
                                            <label class="{{$class1}} col-start-8  col-span-3 items-center">Production Discipline</label>
                                            <span class="{{$class2}}  col-start-11 col-span-2">{{$basicInfoData['prod_discipline_name']}}</span>
                                        </div>
                                    @else
                                    <div class="grid grid-cols-12 text-right ">
                                        <label class="{{$class1}} col-start-1   col-span-3">Production Routing</label>
                                        <span class="{{$class2}}  col-start-4   col-span-9">{{$basicInfoData['prod_routing_name']}}</span>
                                    </div>

                                    @endif
                            </div>
                        </div>
                </div>
                {{-- TABLES --}}
                <div class="">
                    <h4 class=" font-medium leading-tight text-2xl text-black my-2 text-left dark:text-gray-300 pt-4" id="" title="" style="scroll-margin-top: 90px;">Detail Report</h4>
                    <x-renderer.report.pivot-table 
                        showNo={{true}} 
                        :tableColumns="$tableColumns" 
                        :dataSource="$tableDataSource['tableDataSource']" 
                        :tableDataHeader="$tableDataHeader" 
                        maxH='{{$maxH}}' 
                        page-limit="{{$pageLimit}}" 
                        tableTrueWidth={{$tableTrueWidth?1:0}}
                        />
                </div>
        </div>
        <x-renderer.page-break />
        @php
            $dataWidgets = $tableDataSource['dataWidgets'];
            $prodRoutingLink = App\Models\Prod_routing_link::pluck('name', 'id')->toArray();
        @endphp
            @foreach($dataWidgets as $idRoutingLink => $widgets)
                <div id="page{{$idRoutingLink}}" style='' class="w-[1400px] min-h-[990px] items-center bg-white box-border p-8">
                    <div class="pt-5">
                        <x-print.header6 />
                        <x-renderer.heading level=5 xalign='left' class='text-blue-600 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4'>
                          Production Routing Link: <strong>{{$prodRoutingLink[$idRoutingLink]}}</strong></x-renderer.heading>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($widgets as $keyInManage => $values)
                            <div class="col-span-1">
                                <div class="p-6">
                                    <x-renderer.report.pivot-chart4 :data="$values"/>
                                </div>
                            </div>
                            {{-- @dump($values) --}}
                        @endforeach
                    </div>
                </div>
                <x-renderer.page-break />
            @endforeach
</div>
@endsection
