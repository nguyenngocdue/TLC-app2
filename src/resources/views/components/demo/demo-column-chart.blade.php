<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div id="column_chart"></div>

<script>
    var options = {
        chart: {
            type: 'bar',
            height: 350,
        },
        series: [{
            name: 'Net Profit',
            data: [50, 60, 65, 70, 75, 80, 85, 90, 95]
        }, {
            name: 'Revenue',
            data: [85, 90, 100, 110, 115, 120, 125, 130, 135]
        }, {
            name: 'Free Cash Flow',
            data: [40, 45, 50, 55, 60, 65, 70, 75, 80]
        }, {
            name: 'Column 1',
            data: [25, 30, 35, 40, 45, 50, 55, 60, 65]
        }],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: ['February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October']
        },
        yaxis: {
            title: {
                text: '$ (thousands)'
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$ " + val + " thousands";
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#column_chart"), options);
    chart.render();
</script>
