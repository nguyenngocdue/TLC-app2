<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div id="chart-{{$key}}"></div>

<script>
  key = "{{$key}}";
  series = @json($series);
  chartJson = @json($chartJson);

// Define the options for the column chart
 var options =  {
          series: series,
          chart: {
          width: 380,
          type: 'pie',
        },
        labels: chartJson["labels"],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

      var chart = new ApexCharts(document.querySelector("#chart-"+ key), options);
      chart.render();
      

</script>