@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.css">
    </head>
    <div id="chart"></div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var horizontal = false;
            var options = {
                series: [{
                    name: 'Marine Sprite',
                    data: [44, 55, 41, 37, 22, 43, 21]
                }, {
                    name: 'Striking Calf',
                    data: [53, 32, 33, 52, 13, 43, 32]
                }, {
                    name: 'Tank Picture',
                    data: [12, 17, 11, 9, 15, 11, 20]
                }, {
                    name: 'Bucket Slope',
                    data: [9, 7, 5, 8, 6, 9, 4]
                }, {
                    name: 'Reborn Kid',
                    data: [25, 12, 19, 32, 25, 24, 10]
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    stackType: '100%',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false,
                            customIcons: [{
                                icon: '<img src="https://img.icons8.com/material-outlined/24/000000/swap.png">',
                                index: 0,
                                title: 'Toggle Orientation',
                                class: 'custom-icon',
                                click: function(chart, options, e) {
                                    horizontal = !horizontal;
                                    chart.updateOptions({
                                        plotOptions: {
                                            bar: {
                                                horizontal: horizontal,
                                            }
                                        }
                                    });
                                }
                            }]
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: horizontal,
                    },
                },
                stroke: {
                    width: 1,
                    colors: ['#fff']
                },
                title: {
                    text: '100% Stacked Bar'
                },
                xaxis: {
                    categories: [2008, 2009, 2010, 2011, 2012, 2013, 2014],
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + "K"
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    offsetX: 40
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>

@endsection
