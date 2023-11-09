@extends('layouts.app')

@section('content')

<div class="flex justify-center">
    <div class="block">
        <canvas id="myChart" width="400" height="400"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-stacked100@1.0.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-crosshair@1.2.0"></script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
    // Dữ liệu cho biểu đồ
    var data = {
        labels: ['Column 1', 'Column 2', 'Column 3', 'Column 4', 'Column 5'],
        datasets: [{
            data: [30, 50, 40, 100, 100], // Giá trị của các cột tương ứng
            backgroundColor: ['#4dc9f6', '#f67019', '#f53794', '#537bc4', '#acc236']
        }]
    };
    Chart.register(ChartDataLabels);

    // Tùy chọn của biểu đồ
    var options = {
        indexAxis: 'x',
        plugins: {
            interaction: {
                intersect: true,
                mode: 'index',
                crosshair: {
                    line: {
                        color: '#F00',   // Màu của crosshair
                        width: 1,        // Độ dày của crosshair
                        dashPattern: []  // Độ rộng của crosshair
                    },
                    sync: {
                        enabled: false   // Tắt đồng bộ crosshair trên nhiều biểu đồ
                    },
                    zoom: {
                        enabled: false   // Tắt chức năng zoom khi sử dụng crosshair
                    },
                    callbacks: {
                        label: function(context) {
                            // Xác định vị trí của chuột trên trục x
                            var mouseX = context.x;
                            // Xác định giá trị tương ứng trên trục x
                            var xValue = chartConfig.data.labels[Math.round(mouseX / context.xLabelWidth)];
                            // Trả về thông tin của crosshair
                            return xValue;
                        }
                    }
                }
            },
            datalabels: {
                display: true,
                color: 'black',
                anchor: 'end',
                align: 'end',
                font: {
                    weight: 'bold'
                },
                formatter: function(value, context) {
            		return (value.toFixed(2))
				}
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100 // Đặt giá trị tối đa cho trục y
            }
        }
    };

    // Vẽ biểu đồ
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: options
    });
});
</script>

@endsection