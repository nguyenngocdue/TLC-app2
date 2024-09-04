
 <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div id="mixed_chart"></div>

<script>
    var options = {
        chart: {
            height: 350,
            type: 'line',
            stacked: false
        },
        series: [
            {
                name: 'Net Profit',
                type: 'column',
                data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
            },
            {
                name: 'Revenue',
                type: 'column',
                data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
            },
            {
                name: 'Free Cash Flow',
                type: 'line',
                data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
            }
        ],
        stroke: {
            width: [0, 0, 4]
        },
        title: {
            text: 'Financial Data Over Months'
        },
        xaxis: {
            categories: ['February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October']
        },
        yaxis: [
            {
                title: {
                    text: 'Amount (thousands)'
                }
            },
            {
                opposite: true,
                title: {
                    text: 'Free Cash Flow (thousands)'
                }
            }
        ],
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (val) {
                    return "$ " + val + " thousands";
                }
            }
        },
        legend: {
            position: 'bottom'
        }
    };

    var chart = new ApexCharts(document.querySelector("#mixed_chart"), options);
    chart.render();
</script>