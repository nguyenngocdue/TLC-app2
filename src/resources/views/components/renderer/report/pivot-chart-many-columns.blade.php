@php
    $chartType = isset($dataWidgets['chart_type']) ? $dataWidgets['chart_type'] : null;
@endphp

@if($chartType)
<x-renderer.report.chart-bar-many-columns
    key="{{md5($dataWidgets['title_a'].$dataWidgets['title_b'])}}" 
    :meta="$dataWidgets['meta']" 
    :metric="$dataWidgets['metric']"
    chartType="{{$chartType}}" 
    :dimensions="$dataWidgets['dimensions']"
    />
    @else
    <x-renderer.heading class="text-center italic text-gray-500" level=6 >There is no data to display chart.</x-renderer.heading>
@endif