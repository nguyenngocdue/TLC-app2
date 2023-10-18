@php
    $chartType = $dataWidgets['chartType'];
@endphp

@switch($chartType)
    @case('doughnut')
        <x-renderer.report.chart-doughnut 
                key="{{md5($dataWidgets['title_a'].$dataWidgets['title_b'])}}" 
                :meta="$dataWidgets['meta']" 
                :metric="$dataWidgets['metric']"
                chartType="{{$chartType}}" 
                :dimensions="$dataWidgets['dimensions']"
                />
        @break
    @case('bar')
        <x-renderer.report.chart-bar2 
                key="{{md5($dataWidgets['title_a'].$dataWidgets['title_b'])}}" 
                :meta="$dataWidgets['meta']" 
                :metric="$dataWidgets['metric']"
                chartType="{{$chartType}}" 
                :dimensions="$dataWidgets['dimensions']"
                />
        @break
    @default
        @break
        
@endswitch


