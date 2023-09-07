@props(['chartType','showValue'])

<div class="block"><canvas id="{{$key}}"></canvas></div>

{{-- @dump($meta) --}}
{{-- @dump($metric) --}}
{{-- @dump($chartType) --}}
{{-- @dump($showValue) --}}
@once
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
const COLORS = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba','#FF5733','#FFD700','#FF1493','#00FF00','#FF4500','#FF69B4','#FFFF00','#9400D3','#00CED1','#FF7F50'];
</script>
@endonce
<script>
	var key = '{{$key}}'
	var chartType = '{{$chartType}}';
	var datasets = [];
	var indexAxis = 'x'; // Default value
	var scales = {};
		if ('{{$chartType}}' === 'horizontal_bar') {
			datasets = {!! json_encode($meta['numbers']) !!};
			// Customize options for horizontal bar chart
			chartType = 'bar';
			indexAxis = 'y';  
			scales = {
				x: { stacked: true }, // Customize scales for horizontal bar chart
				y: { stacked: true }
			};
			datasets.forEach(function(dataset, index) {
				dataset.backgroundColor = COLORS[index];
			});

		} else {
			// Create a default dataset structure
			datasets.push({
			label: '',
			data: {!! json_encode($meta['numbers']) !!},
			backgroundColor: Object.values(COLORS)
			});
		}
	if('{{$showValue}}' !== '') {

	}
	Chart.register(ChartDataLabels);
  // Create the Chart.js configuration
	chartConfig['{{$key}}'] = {
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
			legend: { position: 'bottom' },
			datalabels:{
				display: '{{$showValue}}'*1,
				anchor: 'end'
				,align: 'start'
				,color: 'white'
				,backgroundColor: 'rgba(0, 0, 0, 0.5)'
				,borderColor: 'white'
				,borderWidth: 1
				,borderRadius: 6
				,formatter: function(value, context) {
            		return value + '\n' + '('+ (value*100/{{$meta['max']}}).toFixed(2) + '%' + ')'
				}
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
  new Chart(chartElement, chartConfig['{{$key}}']);
</script>


