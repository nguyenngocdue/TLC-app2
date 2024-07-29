<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div id="chart-{{ $key }}"></div>
<script>
    const key = "{{ $key }}";
    const series = @json($series);
    const chartOptionStr = @json($chartOptionStr);
    const chartOptions = JSON.parse(chartOptionStr);
    console.log(chartOptions);

    // Define the options for the column chart
    const options = {
        series: series,
        ...chartOptions
    };

    const chart = new ApexCharts(document.querySelector("#chart-" + key), options);
    chart.render();
</script>
