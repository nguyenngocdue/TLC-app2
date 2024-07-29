<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div id="chart-{{ $key }}"></div>
{{-- @dump($jsonChart) --}}
<script>
    var key = "{{ $key }}";
    var series = @json($series);

    // JSON cấu hình từ phía server
    var jsonChart = {!! $jsonChart !!};

    // Parse the JSON configuration
    var chartOptions = JSON.parse(JSON.stringify(jsonChart));

    // Check if xaxis and tooltip objects exist and replace stringified functions with actual functions
    if (chartOptions.xaxis && chartOptions.xaxis.labels) {
        chartOptions.xaxis.labels.formatter = function(val) {
            return val + 'K';
        };
    }

    if (chartOptions.tooltip && chartOptions.tooltip.y) {
        chartOptions.tooltip.y.formatter = function(val) {
            return val + 'K';
        };
    }



    var options = {
        series: series,
        ...chartOptions
    };
    console.log(chartOptions);
    var chart = new ApexCharts(document.querySelector("#chart-" + key), options);
    chart.render();
    // */
</script>
