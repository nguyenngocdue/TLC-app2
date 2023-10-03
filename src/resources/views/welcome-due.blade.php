@extends('layouts.app')
@section('content')

<canvas id="myChart" width="400" height="400"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>

<script>
// Lấy tham chiếu đến canvas
var ctx = document.getElementById('myChart').getContext('2d');

// Dữ liệu cho biểu đồ
var data = {
    labels: ['A', 'B', 'C'],
    datasets: [
        {
            label: 'A',
            data: [20],
            backgroundColor: 'rgba(255, 99, 132, 0.5)'
        },
        {
            label: 'B',
            data: [30],
            backgroundColor: 'rgba(54, 162, 235, 0.5)'
        },
        {
            label: 'C',
            data: [50],
            backgroundColor: 'rgba(255, 206, 86, 0.5)'
        }
    ]
};

// Cấu hình cho biểu đồ
var options = {
    scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    }
};

// Tạo biểu đồ bar
var myChart = new Chart(ctx, {
    type: 'bar',
    data: data,
    options: options
});
</script>

@endsection
