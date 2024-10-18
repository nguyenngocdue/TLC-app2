<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div class="border-2 border-gray-600 p-1 {{$class}}">
    <div id="{{$key}}"></div>
</div>

<script>
    var key = '{{$key}}';
    var options = @json($jsonOptions);
    //console.log(options, key)
    var chart = new ApexCharts(document.getElementById(key), options);
    chart.render();
</script>