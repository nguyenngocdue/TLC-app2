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
{{-- <html :class="{ 'dark': isDark }" x-data="alpineData()" x-ref="alpineRef"  lang="en"> --}}

@once
   <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>
@endonce

<canvas id="myBarChart" width="600" height="400"></canvas>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("myBarChart").getContext("2d");

    const data = {
        labels: ["Engineering", "Quality Control", "Manufacturing", "Logistics", "Project Management"],
        datasets: [
            {
                label: "Design",
                data: [120, 150, 90, 200, 130],
                backgroundColor: "#33dea2",
                borderColor: "transparent",
                borderWidth: 2,
                borderRadius: 5
            },
            {
                label: "Workmanship",
                data: [80, 110, 75, 180, 95],
                backgroundColor: "#0526ff",
                borderColor: "transparent",
                borderWidth: 2,
                borderRadius: 5
            }
        ]
    };

    const options = {
        height: 415,
        responsive: true,
        layout: {
            padding: {
                top: 15,
                bottom: 10
            }
        },
        maintainAspectRatio: false,
        indexAxis: "x",
        params : {
            filterParams: {
                sub_project_id:107,
                prod_routing_id:49,
                prod_discipline_id:null
            },
            labelIds : [1,2,3,4,5],
            dataIndexLabel : "prod_discipline_id"            
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: "Disciplines",
                    font: {
                        size: 12,
                        weight: "bold"
                    },
                    color: "#3E3E3F"
                },
                ticks: {
                    autoSkip: false
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: "Hours",
                    font: {
                        size: 12,
                        weight: "bold"
                    },
                    color: "#3E3E3F"
                }
            }
        },
        plugins: {
            legend: {
                position: "bottom"
            },
            tooltip: {
                callbacks: {
                    label: function (context) {
                        let val = context.raw;
                        return val === 0 ? '' : val.toFixed(0) + ' hours';
                    }
                }
            },
            title: {
                display: true,
                text: "Total Rework Hours (Design/Workmanship) by Discipline",
                align: "center",
                color: "#373D3F",
                font: {
                    size: 18,
                    weight: "bold"
                },
                padding: {
                    bottom: 20
                }
            },
            datalabels: {
                display: true,
                color: "#304758",
                font: {
                    size: 12,
                    weight: "bold"
                },
                anchor: "end",
                align: "start",
                offset: -20,
                formatter: function (value) {
                    return value === 0 ? '' : value;
                }
            },
            zoom: {
                pan: {
                    enabled: true,
                    mode: "x"
                },
                zoom: {
                    wheel: {
                        enabled: true
                    },
                    pinch: {
                        enabled: true
                    },
                    mode: "x"
                }
            }
        },
        onClick: function (event, elements, configs) {
            const [clickedElement] = elements;
            if (!clickedElement) return;

            const { index: dataPointIndex, datasetIndex } = clickedElement;
            console.log(clickedElement);

            // Extract data and configuration objects with clearer naming
            const { labels } = this.data;
            const { filterParams, labelIds, dataIndexLabel } = configs.config._config.options.params;
            const labelIdForDataPoint = labelIds[dataPointIndex];
            const { datasets } = configs.config._config.data;
            const selectedDatasetLabel = datasets[datasetIndex].label;

            // Define parameters object with names that clarify their purpose
            const requestParams = {
                ...filterParams,
                label_id: labelIdForDataPoint,
                data_index_label: dataIndexLabel,
                dataset_name: selectedDatasetLabel,
            };

            console.log(requestParams);
        }

    };


    new Chart(ctx, {
        type: "bar",
        data: data,
        options: options,
        plugins: [ChartDataLabels]
    });
});
</script>




                
@endsection
