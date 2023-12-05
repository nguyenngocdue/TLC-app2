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


<div class="px-4">
    @include('components.reports.shared-parameter')
</div>
<br />
{{-- @dd($tableDataSource) --}}
<div class="flex justify-center bg-only-print px-4">
    <div class="md:px-4">
        <div style='' class="min-w-[990px] min-h-[990px] items-center bg-white box-border p-8">
            <div class="pt-5">
                @include('reports.include-document-ghg-sheet-070')
            </div>
        </div>
    </div>
</div>
@endsection
