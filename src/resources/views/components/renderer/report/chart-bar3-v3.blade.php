<div class="flex justify-center">
	<div class="block" title="{{$titleTooltip}}">
		<canvas id="{{$key}}" width={{$dimensions['width'] ?? 400}} height={{$dimensions['height'] ?? 400}}></canvas>
	</div>
</div>
@if(isset($dimensions['resetZoom']) && $dimensions['resetZoom'])
	<div class="no-print">
		<x-renderer.button type="info" id="resetZoom">Reset Zoom</x-renderer.button>
	</div>
@endif

{{-- @dump($chart_type) --}}
{{-- @dd($dimensions) --}}
{{-- @dump($meta) --}}
{{-- @dump($lineSeries['grid_display']) --}}

@once
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>
@endonce

<script>
	var key = '{{$key}}'
	var chartType = '{{$chart_type}}';
	var indexAxis =  {!! json_encode($dimensions['indexAxis'] ?? 'x')  !!};
	var scales = {};
	var lessThen100 =  {!! json_encode($dimensions['lessThen100'] ?? false)  !!}
	var meta = {!! json_encode($meta) !!}
	var colors = generateColors(meta.count);

	datasets = {!! json_encode($meta['datasets']) !!};
	scales = {
		x: {
				suggestedMax: {!! json_encode($dimensions['scaleMaxX'] ?? null) !!},
				barPercentage: 0.5,
				stacked: {!! $dimensions['stackX'] ?? 'false' !!},
				//max: {!! $dimensions['scaleMaxX'] ?? 'null' !!}, 
				ticks: {
					stepSize: {!! json_encode($dimensions['stepSizeX'] ?? null)!!},
					display: {!! json_encode($dimensions['displayTicksX'] ?? true) !!},
					font: {
						size:  {!! $dimensions['fontSizeAxisXY'] ?? 14 !!}, 
					},
				},
				title: {
					display: {!! json_encode($dimensions['displayTitleX'] ?? true) !!},
					text: '{!! $dimensions['titleX'] ?? null !!}',
					font: {
						size: {!! $dimensions['fontSize'] ?? 14 !!}, 
						weight: 'bold' 
					}
				},
			},
		y: {
			barPercentage: 1,
			suggestedMax: {!! $dimensions['scaleMaxY'] ?? 'null' !!},
			stacked:  {!! $dimensions['stackY'] ?? 0 !!},
			beginAtZero: true,
			ticks: {
				display: {!! json_encode($dimensions['displayTicksY'] ?? true) !!},
				font: {
					size:  {!! $dimensions['fontSizeAxisXY'] ?? 14 !!}, 
					weight: 'bold'
				},
				stepSize: {!! $dimensions['stepSizeY'] ?? 'null' !!},
				//callback: {!! isset($dimensions['legendY']) && $dimensions['legendY'] ? "customXAxisTickCallback"  : 'null' !!}, 

			},
			title: {
				position: 'top',
				display:  {!! json_encode($dimensions['displayTitleY'] ?? true) !!},
				text: {!! json_encode($dimensions['titleY'] ?? true) !!}, 
				font: {
					size: {!! $dimensions['fontSize'] ?? 14 !!}, 
					weight: 'bold' 
				}
			}
		},
		y1: {
			barPercentage: 1,
			display: true,
			position: 'right',
			min: 0, 
			max: {!! json_encode($dimensions['scale_max_y1'] ?? 100) !!},
			ticks: {
				stepSize: 10,
				display: {!! json_encode($dimensions['displayTicksY1'] ?? false) !!},
				callback: function(value, index, values) {
					return value;
				}
			},
			grid:{
				display: {!! json_encode($lineSeries['grid_display'] ?? false) !!},
            },
		}
	};
	datasets.forEach(function(dataset, index) {
		dataset.backgroundColor = colors[index];
	});

	//console.log(datasets.data)
	Chart.register(ChartDataLabels);
  // Create the Chart.js configuration
	var chartConfig  = {
		//type: chartType,
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
					display: {!! json_encode($dimensions['displayLegend'] ?? false) !!},
					position: 'bottom'
					,labels: {
						color: 'rgba(0, 0, 0, 0.7)'
						,font: {
							size: 16,
						}
						,padding: 16
					}
			},
			datalabels:{	
				display: {!! ($dimensions['displayTitleOnTopCol'] ?? false) ? 'function(context) {
							
                            return  context.dataset.type !== "line"
									&& context.dataset.data[context.dataIndex] !== 0 
                                    && context.dataset.data[context.dataIndex] !== "" 
                                    && context.dataset.data[context.dataIndex] !== null;
                        }' : 'false' !!}
				,anchor: 'end'
				,align: 'start'
				,color: '#000000'
				,backgroundColor: {!! json_encode($dimensions['backgroundColor'] ?? null) !!}
				,borderColor: {!! json_encode($dimensions['borderColor'] ?? '#000000') !!}
				,borderWidth: {!! json_encode($dimensions['borderWidth'] ?? 0) !!}
				,borderRadius:  {!! json_encode($dimensions['borderRadius'] ?? 0) !!}
				,font: {
					size: {!! json_encode($dimensions['dataLabelsSize'] ?? 14) !!}
				},
				rotation:  {!! json_encode($dimensions['dataLabelRotation'] ?? 0) !!},
				offset: function(context) {
					if(context.dataset.type === 'line'){
						return 0
					} else{
						return -45
					}
                },
				formatter: function(value, context) {
                    return value.toFixed(2);
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
    //document.getElementById('resetZoom').addEventListener('click', function () {
    //    chart.resetZoom();
    //});

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


