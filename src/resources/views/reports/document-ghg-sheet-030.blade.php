@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')


@php
    $widthCell = 60;
    $class1 = "bg-white border-gray-600 border-l border-b p-2";
    $class2 =" bg-gray-100 px-4 py-3 border-gray-600 p-2";
    $modelLink =  App\Utils\Support\Report::assignValues($params);
    $timeValues =  $modelLink['timeValues'];
    $topNameCol =  $modelLink['topNameCol'];
    $columnName = $modelLink['columnName'];
    $years = $params['year'];
    $layout = 'max-w-[1920px] ';
@endphp


<div class="px-4">
    @include('components.reports.shared-parameter')
</div>

@php
    $dataGhgSheet070 = $tableDataSource['ghg_sheet_070'];
@endphp

<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        {{-- Ggh_sheet-030 --}}
        <div style='' class="max-w-[1800px] min-h-[990px] items-center bg-white box-border p-8">
            <div class="pt-5">
                @include('reports.include-document-ghg-sheet-030')
            </div>
        </div>
        <x-renderer.page-break />
        {{-- Ggh_sheet-070 --}}
        <div style='' class="max-w-[1800px] min-h-[990px] items-center bg-white box-border p-8">
            <div class="pt-5">
                @include('reports.include-document-ghg-sheet-070', ['tableDataSource' => $dataGhgSheet070])
            </div>
        </div>
    </div>
</div>
@endsection
