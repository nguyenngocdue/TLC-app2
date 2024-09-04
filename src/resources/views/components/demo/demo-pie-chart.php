<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div id="pie_chart"></div>

<script>
    // Data aggregated for each category
    var netProfit = [44, 55, 57, 56, 61, 58, 63, 60, 66].reduce((a, b) => a + b, 0);
    var revenue = [76, 85, 101, 98, 87, 105, 91, 114, 94].reduce((a, b) => a + b, 0);
    var freeCashFlow = [35, 41, 36, 26, 45, 48, 52, 53, 41].reduce((a, b) => a + b, 0);

    var options = {
        chart: {
            type: 'pie',
            height: 350
        },
        series: [netProfit, revenue, freeCashFlow],
        labels: ['Net Profit', 'Revenue', 'Free Cash Flow'],
        colors: ['#00E396', '#008FFB', '#FEB019'],
        title: {
            text: 'Distribution of Financial Metrics',
            align: 'left'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                return opts.w.config.labels[opts.seriesIndex] + ": " + val.toFixed(1) + "%"
            }
        },
        tooltip: {
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

    var chart = new ApexCharts(document.querySelector("#pie_chart"), options);
    chart.render();
</script>