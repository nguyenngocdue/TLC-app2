@props(['chartType'])

<div class="block"><canvas id="{{$key}}"></canvas></div>

{{-- @dump($meta) --}}
{{-- @dump($metric) --}}
{{-- @dump($chartType) --}}
@once
<script>
//const COLORS = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba'];
const COLORS = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba','#FF5733','#FFD700','#FF1493','#00FF00','#FF4500','#FF69B4','#FFFF00','#9400D3','#00CED1','#FF7F50'];
var c = {}
</script>
@endonce

<script>
  var key = '{{$key}}';
  var chartType = '{{$chartType}}';
  var datasets = [];
  var indexAxis = 'x'; // Default value
  var scales = {};

  if (chartType === 'horizontal_bar') {
    // If chartType is 'horizontal_bar', customize settings for horizontal bar chart
    datasets = {!! json_encode($meta['numbers']) !!};
    // Customize options for horizontal bar chart
    chartType = 'bar'; // Change chart type to 'bar'
    indexAxis = 'y';  // Change axis for labels to 'y'
    scales = {
      x: { stacked: true }, // Customize scales for horizontal bar chart
      y: { stacked: true }
    };
    // Define custom colors for datasets in the horizontal bar chart
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

  // Create the Chart.js configuration
  var chartConfig = {
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
          datalabels: {
              anchor: 'end'
              , align: 'start'
              , formatter: (value, context) => {
                  return value + '%';
              }
              , color: 'white'
              , font: {
                  size: 14
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

