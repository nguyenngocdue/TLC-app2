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
        indexAxis: 'y',
        plugins: {
            datalabels: {
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
            },
            x: {
                max: 120,
                ticks:{
                    callback: customXAxisTickCallback
                },
                // Tăng giá trị barPercentage và categoryPercentage để tăng kích thước của các cột
                barPercentage: 0.8, // Giá trị này thể hiện tỷ lệ chiều rộng của các cột so với khoảng trống giữa chúng
                categoryPercentage: 0.2 // Giá trị này thể hiện tỷ lệ chiều rộng của các cột so với toàn bộ khoảng trống trên trục x
            }
        }
    };

    // Vẽ biểu đồ
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: options
    });
});


</script>


<script>
function customXAxisTickCallback(value, index, values) {
    if (value === 120) {
        return ''; // Trả về một chuỗi trống để ẩn giá trị 120
    }
    return value;
}
</script>
@endsection
