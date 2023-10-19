@extends('layouts.app')
@section('content')
@php
#dd($widget);
@endphp

@once
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.9.3"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.js"></script>

<script>
const COLORS = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba','#FF5733','#FFD700','#FF1493','#00FF00','#FF4500','#FF69B4','#FFFF00','#9400D3','#00CED1','#FF7F50'];
</script>
@endonce



<canvas id="myBarChart"></canvas>
<script>
// Lấy tham chiếu đến canvas và context
var canvas = document.getElementById('myBarChart');
var ctx = canvas.getContext('2d');

// Dữ liệu cho biểu đồ
var data = {
    labels: ['AAAAA', 'BBBB', 'CCCC'],
    datasets: [{
        data: [20, 30, 50],
        backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)', 'rgba(255, 206, 86, 0.2)'],
        borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)', 'rgba(255, 206, 86, 1)'],
        borderWidth: 1
    }]
};

var options = {
    scales: {
        y: {
            beginAtZero: true
        }
    },
    plugins: {
        legend: {
            display: true,
            position: 'bottom' // Đặt vị trí chú thích ở dưới cùng
        }
    }
};

var myBarChart = new Chart(ctx, {
    type: 'bar',
    data: data,
    options: options
});


</script>



@endsection
