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
            labels: ['Gaseous Fuel', 'Refrigerants', 'Own Passenger Vehicles', 'Delivery  Controlled Vehicles', 'Electricity', 'Water Supply  Treatment', 'Materials', 'Waste Disposal', 'Bussiness Travel Land  Sea', 'Hotel Stay', 'Business Travel  Air', 'Freighting goods Land  Sea', 'Freight Goods  Air', 'Employee Commuting', 'Manage Asset  Electricity', 'Manage Asset  Vehicle', 'Work From Home'],
            datasets: [
                         {
                            label: 'Tên Đường Line',
                            type: 'line', // Loại biểu đồ là 'line'
                            yAxisID: 'y', // Sử dụng trục y bên phải cho đường
                            data: [  9.84, 0.0, 10.21, 14.88, 503.68, 653.14, 7151.66, 17.75, 2.53, 11.33, 29.71, 1.49, 32.93, 3.54, 0.0, 10.93, 0.0], // Dữ liệu của đường
                            borderColor: '##110f2e', // Màu sắc của đường
                            fill: false, // Không fill màu dưới đường,
                            pointBackgroundColor: '#fff',
                            count: 17,
                            max: 762.877,
                            borderWidth: 1,
                            borderColor: "#10ee50",
                            pointStyle: "circle",
                            tension: 0.5,
                            backgroundColor: [ "#4dc9f6", "#f67019", "#f53794", "#537bc4", "#acc236", "#166a8f", 
                                                "#00a950", "#58595b", "#8549ba", "#4dc9f6", "#4dc9f6", "#4dc9f6", 
                                                "#4dc9f6", "#4dc9f6", "#4dc9f6", "#4dc9f6", "#4dc9f6"]
                        },
                        {
                            count: 17,
                            max: 7151.66,
                            tension: 0.5,
                            borderWidth: 2,
                            borderColor: "#6a329f",
                            pointStyle: "circle",
                            yAxisID: 'y', // Sử dụng trục y bên trái
                            data: [  3.541, 0.691, 7.528, 15.351, 244.07, 470.356, 762.877, 0.641, 2.521, 92.789, 45.033, 1.364, 5.075, 82.584, 0.0, 6.339, 0.0],
                            backgroundColor: [ "#4dc9f6", "#f67019", "#f53794", "#537bc4", "#acc236", "#166a8f", 
                                                "#00a950", "#58595b", "#8549ba", "#4dc9f6", "#4dc9f6", "#4dc9f6", 
                                                "#4dc9f6", "#4dc9f6", "#4dc9f6", "#4dc9f6", "#4dc9f6"]
                        }
            
                      
                ]
		},
		options: {
		responsive: true,

		scales: {
            x: {
                display: true,
            },
            y: {
                display: true,
            },
            y1: {
                display: false,
                type: 'linear',
                position: 'right',
                min: 0,
              
                grid: {
                    display: false,
                    borderColor: '#4dc9f6',
                },
            }
        },
		indexAxis: 'y',
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
		}
		}
	};
var chartElement = document.getElementById('myChart');
// Create a new Chart.js chart with the specified element and configuration
var chart = new Chart(chartElement, chartConfig);
console.log(chart.data.datasets)

</script>

@endsection