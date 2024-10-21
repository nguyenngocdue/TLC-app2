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
    
    // Chuyển đổi formatter từ chuỗi thành hàm JavaScript
    if (typeof options.dataLabels.formatter === 'string') {
        options.dataLabels.formatter = eval("(" + options.dataLabels.formatter + ")");
    }

    if (typeof options.tooltip.y.formatter === 'string') {
        options.tooltip.y.formatter = eval("(" + options.tooltip.y.formatter + ")");
    }

    // Khởi tạo ApexChart với các options đã xử lý
    var chart = new ApexCharts(document.getElementById(key), options);
    chart.render();
</script>
