<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div id="{{$key}}"></div>
<script>
    var key = '{{$key}}';
    var options = @json($jsonOptions);
    //console.log(option)
    var chart = new ApexCharts(document.querySelector("#" + key), options);
    chart.render();
</script>