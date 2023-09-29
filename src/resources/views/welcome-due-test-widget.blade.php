@extends('layouts.app')
@section('content')
@php
#dd($widget);
@endphp

 {{-- <x-renderer.card title="">
    <div class="mb-8 grid gap-6 2xl:grid-cols-5 xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1">
        <x-renderer.card title="{{$widget['title_b']}}" tooltip="{{$widget['name']}}">
</x-renderer.card>
</div>
</x-renderer.card> 

<x-dashboards.widget-groups /> --}}

<canvas id="myBarChart"></canvas>
<script>
// Lấy tham chiếu đến canvas và context
var canvas = document.getElementById('myBarChart');
var ctx = canvas.getContext('2d');

// Dữ liệu cho biểu đồ
var data = {
    labels: ['New', 'Progress', 'On Hold'],
    datasets: [{
        label: 'New',
        data: [30],
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
    }, {
        label: 'Progress',
        data: [40],
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
    }, {
        label: 'On Hold',
        data: [50],
        backgroundColor: 'rgba(255, 206, 86, 0.2)',
        borderColor: 'rgba(255, 206, 86, 1)',
        borderWidth: 1
    }]
};

// Cấu hình biểu đồ
var options = {
    scales: {
        y: {
            beginAtZero: true
        }
    },
    plugins: {
        legend: {
            display: true,
            position: 'top' // Hiển thị legend ở trên cùng
        }
    }
};

// Tạo biểu đồ cột
var myBarChart = new Chart(ctx, {
    type: 'bar',
    data: data,
    options: options
});

</script>



@endsection
