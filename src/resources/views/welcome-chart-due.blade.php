@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

@php 
    $currentDate = str_replace('Nguyen', 'Mr.', 'Nguyen Ngoc Due');
    $fromDate = new DateTime('2024-09-18 08:58:59');
    $fromDate = $fromDate->format('Y-m-d');
@endphp

@php
    $entityType = '1a';
@endphp

{{-- Load Chart.js and plugins --}}
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>

<div class="w-1/2">
    <canvas id="reworkChart" width="800" height="400"></canvas>
</div>

@php
 $months = [1,2,3,4,5,6,7];
@endphp

<script>
    const ctx = document.getElementById('reworkChart').getContext('2d');
    var months = {!! json_encode($months) !!};

    // Set up placeholder labels and data
    const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July']; // Replace with Utils.months({ count: 7 }) if Utils is available
    const data = {
        labels: labels,
        datasets: [
            {
                label: 'Dataset 1',
                data: [12, 19, 3, 5, 2, 3, 7], // Replace with Utils.numbers(NUMBER_CFG) if available
                backgroundColor: 'rgba(255, 99, 132, 0.7)', // Replace with Utils.CHART_COLORS.red if available
            },
            {
                label: 'Dataset 2',
                data: [8, 11, 13, 7, 10, 6, 9], // Replace with Utils.numbers(NUMBER_CFG) if available
                backgroundColor: 'rgba(54, 162, 235, 0.7)', // Replace with Utils.CHART_COLORS.blue if available
            },
            {
                label: 'Dataset 3',
                data: [4, 5, 6, 3, 2, 5, 8], // Replace with Utils.numbers(NUMBER_CFG) if available
                backgroundColor: 'rgba(75, 192, 192, 0.7)', // Replace with Utils.CHART_COLORS.green if available
            },
        ]
    };

    // Chart configuration
    const config = {
        type: 'bar',
        data: data,
        options: {
            params : {
                popupReportId: 67,
                labelIds : months,
                dataIndexLabel : "month",
                datasetVariable : "rework_type"
            },

            plugins: {
                title: {
                    display: true,
                    text: 'Chart.js Bar Chart - Stacked'
                }
            },
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true
                }
            },
            onClick : function (event, elements, configs) {
                    const [clickedElement] = elements;
                    if (!clickedElement) return; 
                    const { index: dataPointIndex, datasetIndex } = clickedElement; 
                    const {popupReportId, labelIds, dataIndexLabel, datasetVariable } = configs.config._config.options.params; 
                    const labelIdForDataPoint = labelIds[dataPointIndex]; 
                    const { datasets } = configs.config._config.data;
                    const selectedDatasetLabel = datasets[datasetIndex].label;
                    const requestParams = { popupReportId: popupReportId, labelId: labelIdForDataPoint, dataIndexLabel: dataIndexLabel, datasetVariable: datasetVariable, [datasetVariable] : selectedDatasetLabel};
                    console.log(requestParams);

                    showModal(requestParams) ;       
                }
        }
    };

    // Instantiate the Chart
    new Chart(ctx, config);
</script>

@endsection
