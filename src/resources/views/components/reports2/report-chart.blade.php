<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div id="{{$key}}"></div>
<script>
    var key = '{{$key}}';
    var options = @json($jsonOptions);
    //console.log(options, key)
    var chart = new ApexCharts(document.getElementById(key), options);
    chart.render();
</script>