@php
    $chartType = $dataSource['chart_type'];
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
    {{-- @dd($dataWidgets) --}}
        <x-renderer.report.chart-bar2v2 
                :dataSource="$dataSource"
            />
        @break
    @case('bar_two_columns')
        <x-renderer.report.chart-bar3 
            :dataSource="$dataSource"
            :dataWidgets="$dataWidgets"
            />
        @break
    @default
        @break
        
@endswitch


