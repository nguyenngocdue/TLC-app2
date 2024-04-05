@php
    $chartType = $dataWidgets['chart_type'];
@endphp

<x-renderer.report.chart-bar-many-columns
    key="{{md5($dataWidgets['title_a'].$dataWidgets['title_b'])}}" 
    :meta="$dataWidgets['meta']" 
    :metric="$dataWidgets['metric']"
    chartType="{{$chartType}}" 
    :dimensions="$dataWidgets['dimensions']"
    />



