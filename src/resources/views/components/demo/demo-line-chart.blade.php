
 <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div id="line_chart"></div>

<script>
    var options = {
        chart: {
            type: 'line',
            height: 350
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
        xaxis: {
            categories: ['February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October']
        },
        yaxis: {
            title: {
                text: '$ (thousands)'
            },
            min: 0,
            max: 120,
        },
        markers: {
            size: 4
        },
        dataLabels: {
            enabled: true,
        },
        title: {
            text: 'Net Profit, Revenue, and Free Cash Flow Over Months',
            align: 'left'
        },
        legend: {
            position: 'bottom'
        }
    };

    var chart = new ApexCharts(document.querySelector("#line_chart"), options);
    chart.render();
</script>