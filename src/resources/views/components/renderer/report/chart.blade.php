@props(['chartType'])

<div class="block"><canvas id="{{$key}}"></canvas></div>

{{-- @dump($meta) --}}
{{-- @dump($metric) --}}
{{-- @dump($chartType) --}}
@once
<script>
const COLORS = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba'];
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
        title: { display: false, text: 'Chart.js Doughnut Chart' }
      }
    }
  };

  // Get the DOM element with the specified '{{$key}}' ID
  var chartElement = document.getElementById('{{$key}}');

  // Create a new Chart.js chart with the specified element and configuration
  new Chart(chartElement, chartConfig);
</script>

