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
            case 'filter_by_month':
                $params['regex_legend'] = 'Month';
                break;
            case 'filter_by_week':
                $params['regex_legend'] = 'Week';
                break;
            default: $params;
        }
    }
@endphp

<div class="px-4">
    @include('components.reports.shared-parameter')
</div>
<br />
{{-- @dd($tableDataSource) --}}
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
