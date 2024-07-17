<x-renderer.heading level=5 xalign='left'>{{$name}}</x-renderer.heading>
<x-renderer.heading level=6 xalign='left'>{{$description}}</x-renderer.heading>

@switch($chartType)
    @case(681)
        <x-reports2.charts.types.chart-column
            chartType={{$chartType}}
            :chartJson="$chartJson"
            :dataQuery="$dataQuery"
        />
    @case(682)
    @break
@endswitch

