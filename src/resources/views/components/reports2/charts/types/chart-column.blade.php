<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div id="chart"></div>


<script>
  series = @json($series);
  chartJson = @json($chartJson);
  xAxisFn = chartJson["xaxis"];

  if (xAxisFn && xAxisFn.labels && xAxisFn.labels.formatter){
    console.log(xAxisFn.labels.formatter);
        xAxisFn.labels.formatter = new Function("val", "return " + xAxisFn.labels.formatter + ";");
  }
// Define the options for the column chart
 var options = {
          series: series,
          chart: chartJson["chart"],
        plotOptions: chartJson["plotOptions"],
        stroke: {
          width: 1,
          colors: ['#fff']
        },
        title: chartJson["title"],
        xaxis: xAxisFn,
        yaxis: {
          title: {
            text: undefined
          },
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + "K"
            }
          }
        },
        fill: {
          opacity: 1
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left',
          offsetX: 40
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
      

</script>