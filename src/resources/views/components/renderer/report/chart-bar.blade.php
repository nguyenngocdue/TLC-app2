@props(['chartType'])

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
			chartType = 'bar';
			indexAxis = 'x';  
      scales = {
          x: {
              stacked: true,
              ticks: {
                  font: {
                      size: 16,
                      weight: 'bold'
                  }
              }
          },
          y: {
              stacked: true,
              ticks: {
                  font: {
                      size: 16,
                      weight: 'bold'
                  }
              },
              barPercentage: 1.0 // Đặt chiều cao của các cột là 100%
          }
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
	Chart.register(ChartDataLabels);
  // Create the Chart.js configuration
	var chartConfig  = {
		type: chartType,
		data: {
		labels: {!! $meta['labels'] !!},
		datasets: [
		{
			label:'',
			data: {!! $meta['numbers'] !!},
			backgroundColor: Object.values(COLORS),
			
		}
    ]
		},
		options: {
		responsive: true,
		scales: scales,
		indexAxis: indexAxis,
		plugins: {
			legend: { 
					position: 'bottom'
					,labels: {
						color: 'rgba(0, 0, 0, 0.7)'
						,font: {
							size: 16,
						}
						,padding: 16
					},
					//display: false
			},
			datalabels:{
				display: '{{$showValue}}'*1 ?? 0,
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
		},
    scaleOverride: true,
    scaleSteps: 10, // number of ticks
    scaleStepWidth: 10, // tick interval
    scaleBeginAtZero: true
};

  // Get the DOM element with the specified '{{$key}}' ID
  var chartElement = document.getElementById('{{$key}}');

  // Create a new Chart.js chart with the specified element and configuration
  new Chart(chartElement, chartConfig);


</script>


