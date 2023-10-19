@extends('layouts.app')

@section('content')

@once
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.js"></script>
<script>
const COLORS = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba','#FF5733','#FFD700','#FF1493','#00FF00','#FF4500','#FF69B4','#FFFF00','#9400D3','#00CED1','#FF7F50'];
</script>
@endonce

<canvas id="myDoughnutChart"></canvas>

<script>
var data = {
    labels: ['A', 'B', 'C'],
    datasets: [{
        data: [20, 20, 60],
        backgroundColor: ['#4dc9f6', '#f67019', '#f53794'],
        hoverOffset: 4
    }]
};

var config = {
    type: 'doughnut',
    data: data,
    options: {
        plugins: {
            datalabels: {
                display: true,
                color: 'white',
                font: {
                    size: 16
                },
                formatter: function(value, context) {
                    return context.chart.data.labels[context.dataIndex] + ': ' + value + '%';
                }
            }
        }
    }
};

var ctx = document.getElementById('myDoughnutChart').getContext('2d');
var myDoughnutChart = new Chart(ctx, config);
</script>

@endsection
