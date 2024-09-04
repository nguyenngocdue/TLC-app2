@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')
 <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div id="chart"></div>

<script>
    var options = {
        chart: {
            type: 'line',
            height: 350
        },
        series: [
            {
                name: 'Open Issues',
                data: [5, 8, 6, 1, 4, 3, 1, 2, 6, 5, 1, 8],
                color: '#00E396'
            },
            {
                name: 'Closed Issues',
                data: [0, 1, 1, 3, 0, 1, 1, 2, 2, 1, 0, 3],
                color: '#008FFB'
            }
        ],
        xaxis: {
            categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
        },
        yaxis: {
            title: {
                text: 'Issues (qty)'
            },
            min: 0,
            max: 15,
        },
        markers: {
            size: 4
        },
        dataLabels: {
            enabled: true,
        },
        title: {
            text: 'Issues Reported Over the Year',
            align: 'left'
        },
        legend: {
            position: 'bottom'
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>
@endsection
