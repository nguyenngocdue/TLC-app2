<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<x-renderer.heading level=5 xalign='left'>{{$name}}</x-renderer.heading>
<x-renderer.heading level=6 xalign='left'>{{$description}}</x-renderer.heading>
<div id="chart"></div>

<script>
// Define the options for the column chart
var options = {
    series: [{
        name: 'Sales',
        data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
    }],
    chart: {
        type: 'bar',
        height: 350
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
        },
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
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    },
    yaxis: {
        title: {
            text: 'USD (thousands)'
        }
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return "$ " + val + " thousands"
            }
        }
    }
};

// Initialize the chart and attach it to the 'chart' div
var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();
</script>