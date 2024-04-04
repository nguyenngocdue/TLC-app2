@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')

@php
$class1 = 'p-2 border h-full w-full flex border-gray-600 text-base font-medium bg-gray-50 items-center justify-end';
$class2 = 'p-2 border border-gray-600 flex justify-start items-center text-sm font-normal text-left'
@endphp
{{-- "Show utility"  --}}
@php
    $tl = "<div></div>";
    $tc = "<x-reports.utility-report routeName='$routeName'/>"; 
    $tr = "<x-reports.per-page-report typeReport='$typeReport' entity='$entity' routeName='$routeName' page-limit='$pageLimit' formName='updatePerPage' />"; 
@endphp

<div class="grid grid-cols-12 gap-1">
        <div class="col-span-2">
            <div class="no-print justify-end pl-4 pt-5">
                <div class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
                    <x-reports.dropdown6    
                        title="Mode" 
                        name="children_mode" 
                        routeName="{{$routeName}}"
                        :allowClear="false"
                        :dataSource="['filter_by_week' => 'Filter by Week','filter_by_month' => 'Filter by Month']" 
                        typeReport='{{$typeReport}}'
                        entity='{{$entity}}'
                        modeOption='{{$mode}}'
                        :itemsSelected="$params" 
                        />
                </div>
            </div>
        </div>

        <div class="col-span-10">
            <div class="px-4">
                @include('components.reports.shared-parameter')
                @include('components.reports.show-layout2')
            </div>
        </div>
</div>


<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='page-break-after:always!important' class="{{$layout}} items-center bg-white box-border p-8">
            <x-print.header6 />
            <x-renderer.heading level=2 class='text-center pt-4'>QC Acceptance Report</x-renderer.heading>
            {{-- RENDER WIDGET --}}
            <div class="pt-4"></>
            <div class="p-4 border">
                <x-renderer.report.pivot-chart3 
                    key="qaqc_wir_overall_complete_status_all_projects" 
                    :data="$tableDataSource"
                    optionPrint="{{$optionPrint}}"
                    :paramFilters="$params"
                ></x-renderer.report.pivot-chart3>
            </div>
            {{-- RENDER TABLES --}}
            <div class="pt-4">
                @if($params['children_mode'] === 'filter_by_month')
                    <x-renderer.heading level=6 class='text-right italic px-10'>Date of Update: <strong>{{$basicInfoData['date_of_update']}} </strong></x-renderer.heading>
                @endif
                <x-renderer.report.pivot-table 
                    showNo={{true}} 
                    :tableColumns="$tableColumns" 
                    :dataSource="$tableDataSource"
                    page-limit="{{$pageLimit}}"
                     />
            </div>
        </div>
    </div>
</div>
@endsection

<script type="text/javascript">
    var param = {!! json_encode($params) !!}
    var childrenMode = param.children_mode
    console.log(childrenMode)
    document.addEventListener('DOMContentLoaded', function() {
        if (childrenMode == 'filter_by_month') {
            const month = document.getElementById('name_month');
            const _month = document.getElementById('name_only_month');
            const year = document.getElementById('name_year');
            
            year.classList.remove('hidden');
            _month.classList.remove('hidden');
            month.classList.remove('hidden');
        } else {
            const year = document.getElementById('name_year');
            const week = document.getElementById('name_weeks_of_year');
            year.classList.remove('hidden');
            week.classList.remove('hidden');
        }
    });
</script>
