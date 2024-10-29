{{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div class="border-2 border-gray-600 p-1 {{$class}}">
    <div id="{{$key}}"></div>
</div>

<script>
    var key = '{{$key}}';
    var options = @json($jsonOptions);
    //console.log(options, key)
    var chart = new ApexCharts(document.getElementById(key), options);
    chart.render();
</script> --}}


<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div class="border-2 border-gray-600 p-1 {{$class}}">
    <div id="{{$key}}"></div>
</div>

<script>
    var key = '{{$key}}';
    var options = @json($jsonOptions);
    
    // change formatter from string to JavaScript function 
    if (typeof options?.dataLabels?.formatter === 'string') {
        options.dataLabels.formatter = eval("(" + options.dataLabels.formatter + ")");
    }

    if (typeof options?.tooltip?.y?.formatter === 'string') {
        options.tooltip.y.formatter = eval("(" + options.tooltip.y.formatter + ")");
    }

    // add event handler from string to JavaScript function
    if (typeof options.chart?.events?.click === 'string') {
        options.chart.events.click = eval("(" + options.chart.events.click + ")");
    }

    // Hidden label of Y axis
    if (typeof options.yaxis?.labels?.formatter === 'string') {
        options.yaxis.labels.formatter = eval("(" + options.yaxis.labels.formatter + ")");
    }

    var chart = new ApexCharts(document.getElementById(key), options);
    chart.render();
</script>
