@props(['chartType'])
<div class="flex justify-center">
	<div class="block w-[{{$dimensions['width']}}px] h-[{{$dimensions['width']}}px]">
		<canvas id="{{$key}}"></canvas>
	</div>
</div>

{{-- @dump($meta) --}}
{{-- @dump($metric) --}}
{{-- @dump($chartType) --}}
{{-- @dump($showValue) --}}
@once
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
@endonce

<script>
	var key = '{{$key}}';
	var chartType = '{{$chartType}}';
	var chartData = {
		labels: {!! $meta['labels'] !!},
		numbers: {!! $meta['numbers'] !!},
		backgroundColor: Object.values(COLORS)
	};


	var numColors = chartData.labels.length;
	var generatedColors = generateColors(numColors);

	Chart.register(ChartDataLabels);
  // Create the Chart.js configuration
	var chartConfig  = {
		type: chartType,
		data: {
			labels: chartData.labels,
			datasets: [{
				label: "data",
				data: chartData.numbers,
				backgroundColor: generatedColors,
			}]
		},
		options: {
		responsive: true,
		plugins: {
			title:{
                display: {!! $dimensions['displayTitleChart'] ?? 0 !!},
                text:  '{!! $dimensions['titleChart'] ?? null !!}',
                font:{
                    size: {!! $dimensions['fontSizeTitleChart'] ?? 16 !!}, 
                    weight: 'bold' 
                }, 
				position: '{!! $dimensions['positionTitleChart'] ?? 'bottom' !!}',
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
			datalabels:{
				display: 0,
				anchor: 'end'
				,align: 'start'
				,color: 'white'
				,backgroundColor: 'rgba(0, 0, 0, 0.5)'
				,borderColor: 'white'
				,borderWidth: 1
				,borderRadius: 6
				,font: {
					size: 16
				}
			}
		}
		}
	};

  // Get the DOM element with the specified '{{$key}}' ID
  var chartElement = document.getElementById('{{$key}}');

  // Create a new Chart.js chart with the specified element and configuration
  new Chart(chartElement, chartConfig);
</script>


