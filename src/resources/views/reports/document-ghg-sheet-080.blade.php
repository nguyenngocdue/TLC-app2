@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')

{{-- PARAMETERS --}}
@php
$widthCell = 50;
$class1 = "bg-white dark:border-gray-600 border-r";
$class2 =" bg-gray-100 px-4 py-3 border-gray-600 ";
@endphp


@php
    if(isset($params['children_mode'])) {
        $typeFilter = $params['children_mode'];
        switch($typeFilter) {
            case 'filter_by_year':
                $params['regex_legend'] = 'Year';
                break;
            case 'filter_by_half_year':
                $params['regex_legend'] = 'Half Year';
                break;
            default: $params;
        }
    }
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
                        :dataSource="['filter_by_year' => 'Filter by Year','filter_by_half_year' => 'Filter by Half Year']" 
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


<br />
{{-- @dd($tableDataSource['from_oil']) --}}
<div class="flex justify-center bg-only-print px-4">
    <div class="md:px-4">
        <div style='' class="items-center bg-white box-border p-8">
            <div class="pt-5">
                @include('reports.include-document-ghg-sheet-080')
            </div>
        </div>
    </div>
</div>




@endsection
