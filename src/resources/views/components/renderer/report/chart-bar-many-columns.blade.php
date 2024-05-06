@props(['chartType'])
<div class="flex justify-center">
	<div class="block" title="{{$keyNameManage}} (Component Name: PivotChartManyColumns.php)">
		<canvas id="{{$key}}" width={{$dimensions['width'] ?? 400}} height={{$dimensions['height'] ?? 400}}></canvas>
	</div>
</div>
@if(isset($dimensions['resetZoom']))
	<div class="no-print pt-4">
		<x-renderer.button type="info" id="resetZoom" class="border-blue-600 bg-blue-600 text-white hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg active:bg-blue-800 active:shadow-lg">
			Reset Zoom
		</x-renderer.button>
	</div>
@endif
{{-- @dd($dimensions) --}}
@once
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>
@endonce

<script>
	var key = '{{$key}}'
	var chartType = '{{$chartType}}';
	var datasets = [];
	var indexAxis = 'x';
	var scales = {};
	var lessThen100 =  {!! json_encode($dimensions['lessThen100'] ?? false)  !!}
	var meta = {!! json_encode($meta) !!}
		if ('{{$chartType}}' === 'bar_two_columns' || '{{$chartType}}' === 'horizontal_bar' || '{{$chartType}}' === 'bar_many_columns') {
			datasets = {!! json_encode($meta['numbers']) !!};
			chartType = 'bar';
			indexAxis = 'x';  
			scales = {
				x: {
						barPercentage: 0.6,
						stacked: {!! $dimensions['stackX'] ?? 'false' !!},
						max: {!! $dimensions['scaleMaxX'] ?? 'null' !!}, 
						ticks: {
							font: {
								size:  {!! $dimensions['fontSizeAxisXY'] ?? 14 !!}, 
							},
							// change legends on Y axis
							//callback: function(value, index, ticks) {
							//    return '$';
							//}
						},
						title: {
							display: true,
							text: '{!! $dimensions['titleX'] ?? null !!}',
							font: {
								size: {!! $dimensions['fontSize'] ?? 14 !!}, 
								weight: 'bold' 
							}
						},
					},
				y: {
					suggestedMax: {!! $dimensions['scaleMaxY'] ?? 'null' !!},
					barPercentage: 0.8,
					stacked:  {!! $dimensions['stackY'] ?? 0 !!},
					beginAtZero: true,
					//max: {!! $dimensions['scaleMaxY'] ?? 'null' !!}, 
					ticks: {
						font: {
							size:  {!! $dimensions['fontSizeAxisXY'] ?? 14 !!}, 
							weight: 'bold'
						},
						stepSize: {!! $dimensions['stepSizeY'] ?? 'null' !!},
                    	callback: {!! isset($dimensions['legendY']) && $dimensions['legendY'] ? "customXAxisTickCallback"  : 'null' !!}, 

					},
					title: {
						position: 'top',
						display: true,
						text: '{!! $dimensions['titleY'] ?? null !!}', 
						font: {
							size: {!! $dimensions['fontSize'] ?? 14 !!}, 
							weight: 'bold' 
						}
					}
				},
			};
			datasets.forEach(function(dataset, index) {
				var generatedColors = generateColors(meta.count);
				dataset.backgroundColor = generatedColors[index];
			});
		} else {
			// Create a default dataset structure
			datasets.push({
			label: '',
			data: {!! json_encode($meta['numbers']) !!},
			backgroundColor: Object.values(COLORS)
			});
		}
	console.log(datasets)
	Chart.register(ChartDataLabels);
  // Create the Chart.js configuration
	var chartConfig  = {
		type: chartType,
		data: {
		labels: {!! $meta['labels'] !!},
		datasets: datasets
		},
		options: {
		responsive: true,
		scales: scales,
		indexAxis: indexAxis,
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
				 	align: 'center', 
					position: 'bottom'
					,labels: {
						color: 'rgba(0, 0, 0, 0.7)'
						,font: {
							size: 16,
						}
						,padding: 16
                		,usePointStyle: {!! $dimensions['usePointStyle'] ?? 0 !!},
					}
			},
			datalabels:{	
				display: {!! $dimensions['displayTitleOnTopCol'] ? 'function(context) {
                            return context.dataset.data[context.dataIndex] !== 0 
                                    && context.dataset.data[context.dataIndex] !== "" 
                                    && context.dataset.data[context.dataIndex] !== null;
                        }' : 'false' !!}
				,anchor: 'end'
				,align: 'start'
				,color: 'white'
				,backgroundColor: 'rgba(0, 0, 0, 0.5)'
				,borderColor: 'white'
				,borderWidth: 1
				,borderRadius: 6
				,font: {
					size: 16
				},
				rotation:  {!! $dimensions['dataLabelRotation'] ?? 0 !!},
				offset: {!! $dimensions['dataLabelOffset'] ?? 0 !!},
				formatter: function(value, context) {
                    return value +'%';
                }
			},
			zoom: {
					zoom: {
					wheel: {
						enabled: {!! $dimensions['zoomWheelEnabled'] ?? 0 !!},
					},
					pinch: {
						enabled: {!! $dimensions['zoomPinchEnabled'] ?? 0 !!},
					},
					mode: '{!! $dimensions['zoomMode'] ?? 'xy' !!}',
					}
			},
		}
		}
	};

	// Get the DOM element with the specified '{{$key}}' ID
	var chartElement = document.getElementById('{{$key}}');

	// Create a new Chart.js chart with the specified element and configuration
	var chart = new Chart(chartElement, chartConfig);

	// Reset Zoom configuration
    document.getElementById('resetZoom').addEventListener('click', function () {
        chart.resetZoom();
    });

function customXAxisTickCallback(value, index, values) {
        //console.log(lessThen100, chartData.labels[index])
    if(lessThen100){
        if (value <= 100) {
            return value; // to hidden value of legend > 100
        }
    } else{
        return chartData.labels[index];
    }
}
</script>


