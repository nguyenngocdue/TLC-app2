@extends('layouts.app')

@section('content')

<div class="flex justify-center">
    <div class="block">
        <canvas id="myChart" width="1000" height="500"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>

<script>
	var key = 'myChart'
	var chartType = 'bar';
	var datasets = [];
	var scales = {};
//console.log(datasets.data)
Chart.register(ChartDataLabels);

var chartConfig  = {
		type: 'bar',
		data: {
            labels: ['STW1 (PAS Packing and Shipping)', 'STW1 (STW Corner Block)', 'STW1 (STW Modular)', 'STW1 (STW Roof)', 'STW1 (STW Staircase)'],
            datasets: [
                         {
                            label: 'Tên Đường Line',
                            type: 'line', // Loại biểu đồ là 'line'
                            yAxisID: 'y1', // Sử dụng trục y bên phải cho đường
                            data: [35, 40, 60, 80], // Dữ liệu của đường
                            borderColor: '##110f2e', // Màu sắc của đường
                            fill: true, // Không fill màu dưới đường,
                            pointBackgroundColor: '#fff',
                        },
                        {
                            type: 'line', // Loại biểu đồ là 'line'
                            yAxisID: 'y1', // Sử dụng trục y bên phải cho đường
                            data: [84, 75, 62, 22], // Dữ liệu của đường
                            borderColor: '#ff0000', // Màu sắc của đường
                            fill: false, // Không fill màu dưới đường
                            tension: 0.4, // Độ cong của đường (giá trị từ 0 đến 1)
                            borderWidth: 0.5 // Độ dày của đường
                        },
                        {
                            yAxisID: 'y', // Sử dụng trục y bên trái
                            data: [10, 10, 50, 70],
                            backgroundColor: ['#4dc9f6', '#f67019', '#f53794', '#f53794', '#537bc4']
                        }, 
                        {
                            yAxisID: 'y', // Sử dụng trục y bên trái
                            data: [20, 30, 10, 20], 
                            backgroundColor: ['#4dc9f6', '#f67019', '#f53794', '#f53794', '#537bc4']
                        },
                        {
                            yAxisID: 'y1', // Sử dụng trục y bên trái
                            data: [90, 13, 27, 30],
                            backgroundColor: ['#4dc9f9', '#f67019', '#f53799', '#f53799', '#507bc9']
                        }, 
                        {
                            yAxisID: 'y1', // Sử dụng trục y bên phải
                            data: [90, 13, 27, 30], 
                            backgroundColor: ['#4dc9f6', '#f67019', '#f53794', '#f53794', '#507bc4']
                        },
                      
                ]
		},
		options: {
		responsive: true,

		scales: {
                y: {
                    display: true,
                    max: 200,
                    type: 'linear', // Kiểu của trục y (linear cho số hoặc logarithmic cho logarit)
                    position: 'left', // Vị trí của trục y (left cho trục bên trái, right cho trục bên phải)
                },
                y1: {
                    display:true,
                    type: 'linear',
                    position: 'right', // Đặt vị trí của trục y bên phải
                    min: 0, // Giá trị tối thiểu trên trục y bên phải
                    max: 100000, // Giá trị tối đa trên trục y bên phải (ở đây là 100 để hiển thị phần trăm)
                    ticks: {
                        callback: function(value, index, values) {
                            return value + '%'; // Thêm ký tự phần trăm vào giá trị trục y
                        }
                    },
                    grid: {
                        display: true, // Ẩn grid cho trục Y bên phải,
                        borderColor: '#4dc9f6', // Màu sắc của đường

                    },
                }
        },
		indexAxis: 'x',
		plugins: {
			title:{
                display: {!! $dimensions['displayTitleChart'] ?? 0 !!},
                text:  '{!! $dimensions['titleChart'] ?? null !!}',
                font:{
                    size: {!! $dimensions['fontSizeTitleChart'] ?? 16 !!}, 
                    weight: 'bold' 
                }, 
				position: '{!! $dimensions['positionTitleChart'] ?? 'bottom' !!}',
				padding: 30,
            },
			tooltip: {
                enabled: true,
                 callbacks: {
                    label: function(context) {
                            var label = '{!! $dimensions['tooltipLabel'] ?? 'data' !!}'; 
                            var value =  context.raw;
                            return label + ': ' + value;
                    }
                },
            }, 
			legend: { 
					position: 'bottom'
					,labels: {
						color: 'rgba(0, 0, 0, 0.7)'
						,font: {
							size: 16,
						}
						,padding: 16
					}
			},
            zoom: {
					zoom: {
					wheel: {
						enabled: true,
					},
					pinch: {
						enabled:true,
					},
					mode: 'x',
					}
			},
		}
		}
	};
var chartElement = document.getElementById('myChart');
// Create a new Chart.js chart with the specified element and configuration
var chart = new Chart(chartElement, chartConfig);

</script>

@endsection