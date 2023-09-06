@extends('layouts.app')
@section('content')
@php
#dd($widget);
@endphp

{{-- <x-elapse title="Widget group: " />
<div class="w80 h-80 bg-green-600 ">
    <x-renderer.report.chart key="{{md5($widget['title_a'].$widget['title_b'])}}" chartType="{{$widget['chartType']}}" :meta="$widget['meta']" :metric="$widget['metric']" :widgetParams="$widget['params']" />
</div>
<x-dashboards.widget-groups /> --}}

{{-- <x-renderer.card title="">
    <div class="mb-8 grid gap-6 2xl:grid-cols-5 xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1">
        <x-renderer.card title="{{$widget['title_b']}}" tooltip="{{$widget['name']}}">
<x-renderer.report.chart key="{{md5($widget['title_a'].$widget['title_b'])}}" chartType="{{$widget['chartType']}}" :meta="$widget['meta']" :metric="$widget['metric']" :widgetParams="$widget['params']" />
</x-renderer.card>
</div>
</x-renderer.card> --}}
{{-- <x-dashboards.widget-groups />  --}}

<div class=" grid-rows-1 pt-2 flex justify-center">
    <div class="w-1/2 h-1/2">
        <x-renderer.report.pivot-chart key="carbon_footprint_1" :dataSource="$widget"></x-renderer.report.pivot-chart>
    </div>

    <!-- Đối tượng canvas để vẽ biểu đồ -->
    <canvas id="myChart" width="400" height="200"></canvas>

    <script>
        var data = {
            labels: ["Scope 1", "Scope 2", "Scope 3"]
            , datasets: [{
                    label: "010. Gaseous Fuel"
                    , data: [2.39, 0]
                    , backgroundColor: "red"
                }
                , {
                    label: "020. Refrigerants"
                    , data: [0.69, 0]
                    , backgroundColor: "blue"
                }
                , {
                    label: "030. Own Passenger Vehicles"
                    , data: [7.57, 0]
                    , backgroundColor: "green"
                }
                , {
                    label: "040. Delivery & Controlled Vehicles"
                    , data: [17.42, 0]
                    , backgroundColor: "orange"
                }
                , {
                    label: "050. Electricity"
                    , data: [0, 216.91]
                    , backgroundColor: "purple"
                }
                , {
                    label: "060. Water Supply & Treatment"
                    , data: [0,0,100]
                    , backgroundColor: "green"
                }
                , {
                    label: "070. Materials"
                    , data: [0,0,1017.47]
                    , backgroundColor: "yellow"
                }
                , {
                    label: "080. Waste Disposal"
                    , data: [0, 0,70]
                    , backgroundColor: "red"
                }
            ]
        };
        // Lấy thẻ canvas và vẽ biểu đồ ngang
        var ctx = document.getElementById("myChart").getContext("2d");
        var myChart = new Chart(ctx, {
            type: "bar"
            , data: data
            , options: {
                scales: {
                    x: {
                        stacked: true
                    }
                    , y: {
                        stacked: true
                    }
                }
                , indexAxis: 'y'
            }
        });

    </script>
</div>
<x-dashboards.widget-groups />



@endsection
