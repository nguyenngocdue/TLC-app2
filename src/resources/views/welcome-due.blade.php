@extends('layouts.app')

@section('content')

@once
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.js"></script>
<script>
const COLORS = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba','#FF5733','#FFD700','#FF1493','#00FF00','#FF4500','#FF69B4','#FFFF00','#9400D3','#00CED1','#FF7F50'];
</script>
@endonce

<canvas id="myBarChart"></canvas>

<script>
var ctx = document.getElementById('myBarChart').getContext('2d');
var myBarChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['A', 'B', 'C'],
        datasets: [{
            label: 'Thể Hiện 1',
            data: [20, 30, 80],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        },
        {
            label: 'Thể Hiện 2',
            data: [30, 50, 100],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }],
            xAxes: [{
                categoryPercentage: 0.7, // Tỷ lệ giữa các cột
                barPercentage: 0.5, // 100% chiều rộng của các cột được sử dụng, không có khoảng trống
                barThickness: 40 // Đặt giá trị tối đa cho bề rộng của các cột thành 40 pixel
            }]
        },
        plugins: {
            datalabels: {
                display: true,
                color: 'white',
                font: {
                    size: 16
                }
            }
        }
    }
});


</script>

@endsection
