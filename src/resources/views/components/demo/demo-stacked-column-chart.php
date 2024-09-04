<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div id="stacked_column_chart"></div>
<script>
    var options = {
        chart: {
            type: 'bar',
            height: 350,
            stacked: true
        },
        series: [
            {
                name: 'Net Profit',
                data: [44, 55, 57, 56, 61, 58, 63, 60, 66],
                color: '#00E396'
            },
            {
                name: 'Revenue',
                data: [76, 85, 101, 98, 87, 105, 91, 114, 94],
                color: '#008FFB'
            },
            {
                name: 'Free Cash Flow',
                data: [35, 41, 36, 26, 45, 48, 52, 53, 41],
                color: '#FEB019'
            }
        ],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: true,
        },
        stroke: {
            show: true,
            width: 1,
            colors: ['#fff']
        },
        xaxis: {
            categories: ['February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October']
        },
        yaxis: {
            title: {
                text: '$ (thousands)'
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$ " + val + " thousands";
                }
            }
        },
        fill: {
            opacity: 1
        },
        legend: {
            position: 'bottom'
        },
        title: {
            text: 'Stacked Column Chart of Financial Data',
            align: 'left'
        },
    };

    var chart = new ApexCharts(document.querySelector("#stacked_column_chart"), options);
    chart.render();
</script>