
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
            data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
        }, {
            name: 'Revenue',
            data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
        }, {
            name: 'Free Cash Flow',
            data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
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
