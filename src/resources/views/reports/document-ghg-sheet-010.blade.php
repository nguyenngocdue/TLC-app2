@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $titleReport)
@section('tooltip', Str::ucfirst($typeReport)." ".$currentMode)
@section('content')

{{-- PARAMETERS --}}
@php
$widthCell = 50;
$class1 = "bg-white border-gray-600 border-l";
$class2 =" bg-gray-100 px-4 py-3 border-gray-600 ";
$titleColName = isset($params['quarter_time']) ? 'QTR'.$params['quarter_time'] : 'YTD';
$titleColName = isset($params['only_month']) ? 'Total Quantity': $titleColName;
@endphp


<div class="px-4">
    @include('components.reports.shared-parameter')
</div>
<br />
    <div  id="pagedata_summary_report"  style='page-break-after:always!important'  class=" w-[1400px] min-h-[790px] m-auto flex items-center bg-white box-border p-8 relative">
        @include('reports.document-ghg-summary-report-only-table')
    </div>

@endsection
