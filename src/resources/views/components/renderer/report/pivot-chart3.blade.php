@php
    $chartType = $dataWidgets['chart_type'];
@endphp

@switch($chartType)
    @case('doughnut')
    {{-- @dump($dataWidgets) --}}
        <x-renderer.report.chart-doughnut 
                key="{{md5($dataWidgets['title_a'].$dataWidgets['title_b'])}}" 
                :meta="$dataWidgets['meta']" 
                :metric="$dataWidgets['metric']"
                chartType="{{$chartType}}" 
                :dimensions="$dataWidgets['dimensions']"
                />
        @break
    @case('bar')
    {{-- @dump($chartType, $dataWidgets) --}}
        <x-renderer.report.chart-bar2 
                key="{{md5($dataWidgets['title_a'].$dataWidgets['title_b'])}}" 
                :meta="$dataWidgets['meta']" 
                :metric="$dataWidgets['metric']"
                chartType="{{$chartType}}" 
                :dimensions="$dataWidgets['dimensions']"
                />
        @break
    @case('bar_two_columns')
        <x-renderer.report.chart-bar3 
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


