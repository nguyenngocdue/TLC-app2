<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApexCharts Column Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        #chart {
            max-width: 900px;
            margin: 35px auto;
        }
    </style>
</head>
<body>
    <div id="chart"></div>

    <script>
        var options = {
            chart: {
                type: 'bar',
                height: 500,
                stacked: false,
                toolbar: {
                    show: false
                },
                events: {
                    "click": function(event, chartContext, config) {
                        if (typeof config.dataPointIndex !== 'undefined' && config.dataPointIndex >= 0) {
                            var seriesIndex = config.seriesIndex;
                            var dataPointIndex = config.dataPointIndex;
                            var value = chartContext.w.config.series[seriesIndex].data[dataPointIndex];
                            console.log('Value:', chartContext.w.config.xaxis);
                            console.log('Value:', chartContext.w.config.xaxis.categories[dataPointIndex]);
                        }
                    }
        }
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                    endingShape: 'flat',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            colors: [


            function({ seriesIndex, dataPointIndex, w }) {
                if (w.config.xaxis.categories[dataPointIndex] === "TOTAL") {
                    return ["#A020F0", "#18de5e"][seriesIndex]; 
                } else {
                    return seriesIndex === 0 ? "#008FFB" : "#FF4560";  
                }
            }

            ],
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '13px',
                    colors: ['#000']
                },
                offsetY: -20
            },
            series: [{
                name: 'design',
                data: [120138, 307, 836, 2759, 654, 0, 4676]
            }, {
                name: 'workmanship',
                data: [14, 27, 27, 148, 27, 76, 430]
            }],
            xaxis: {
                categories: ['PPR', 'STRUCTURE', 'MEP', 'FIT OUT', 'TILING', 'QAQC', 'TOTAL'],
                categories_id: [1, 2, 3, 4, 5, 6, 7],
                labels: {
                    style: {
                        fontSize: '13px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '13px'
                    },
                    formatter: function (value) { return value === 150000 ? '' :  value;}
                },
                title: {
                    text: 'Number of Defects',
                    style: {
                        fontSize: '14px'
                    }
                }
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '13px'
            },
            grid: {
                borderColor: '#e0e0e0'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
</body>
</html>
