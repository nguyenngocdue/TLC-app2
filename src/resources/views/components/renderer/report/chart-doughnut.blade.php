@props(['chartType'])
<div class="flex justify-center" title="{{$tooltipComponent}}">
	<div class="block">
		<canvas id="{{$key}}" width={{$dimensions['width'] ?? 400}} height={{$dimensions['height'] ?? 400}}></canvas>
	</div>
</div>

{{-- @dump($meta) --}}
{{-- @dump($metric) --}}
{{-- @dump($chartType) --}}
{{-- @dump($showValue) --}}
@once
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.js"></script>
@endonce

<script>
	var key = '{{$key}}';
	var chartType = '{{$chartType}}';
	var chartData = {
		labels: {!! $meta['labels'] !!},
		numbers: {!! $meta['numbers'] !!},
	};


	var numColors = chartData.labels.length;
	var generatedColors = generateColors(numColors);

	//Chart.register(ChartDataLabels);
	// Create the Chart.js configuration
	var chartConfig  = {
		type: 'doughnut',
		data: {
			labels: chartData.labels,
			datasets: [{
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
				display: {!! $dimensions['displayTitleOnTopCol'] ? 'function(context) {
                            return context.dataset.data[context.dataIndex] !== 0 
                                    && context.dataset.data[context.dataIndex] !== "" 
                                    && context.dataset.data[context.dataIndex] !== null;
                        }' : 'false' !!},
				anchor: 'end'
				,align: 'start'
				,color: 'white'
				,backgroundColor: 'rgba(0, 0, 0, 0.5)'
				,borderColor: 'white'
				,borderWidth: 1
				,borderRadius: 6
				,font: {
					size: 16,
					textAlign: 'center'
				},
				formatter: function(value, context) {
					var values = chartData.numbers;
					var total = values.reduce(function(a, b) {return a + b;}, 0);
					var text = value + '\n(' + ((value*100/total).toFixed(2)) +"%)";
                    return ' '.repeat(text.length/2) + text;
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


