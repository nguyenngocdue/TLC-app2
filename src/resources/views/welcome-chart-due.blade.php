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
            colors: ['#1f77b4', '#ff7f0e'],
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
                    formatter: function (value) {
      return value + "$";
    }
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
