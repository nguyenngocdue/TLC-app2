@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')

{{-- PARAMETERS --}}
@php
$widthCell = 50;
$class1 = "bg-white dark:border-gray-600 border-r";
$class2 =" bg-gray-100 px-4 py-3 border-gray-300 ";
$titleColName = isset($params['quarter_time']) ? 'QTR'.$params['quarter_time'] : 'YTD';
$titleColName = isset($params['only_month']) ? 'Total Quantity': $titleColName;
@endphp


<div class="px-4">
    @include('components.reports.shared-parameter')
</div>
<br />
@include('reports.document-ghg-summary-report-only-table')
@endsection
