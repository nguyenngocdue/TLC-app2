@props(['chartType'])

<div class="block"><canvas id="{{$key}}"></canvas></div>

{{-- @dump($meta) --}}
{{-- @dump($metric) --}}
{{-- @dump($chartType) --}}
{{-- @dump($titleChart) --}}

@once
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
const COLORS = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba'];
var c = {}
</script>
@endonce
<script>
  var key = '{{$key}}'
  c[key]={}
  c[key]['element'] = document.getElementById('{{$key}}');
  c[key]['data'] = {
    labels: {!! $meta['labels'] !!},
    datasets: [
      {
        label:'',
        data: {!! $meta['numbers'] !!},
        backgroundColor: Object.values(COLORS),
        
      }
    ]
  };
  c[key]['config'] = {
    type: '{{$chartType}}',
    data: c[key]['data'],
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false,
					position: 'bottom'
					,labels: {
						color: 'rgba(0, 0, 0, 0.7)'
						,font: {
							size: 16,
						}
						,padding: 16
					}
			},
        title: {
          display: true,
          text: '{{$titleChart}}',
          font: {
                    size: 20,
                },   
        },
        datalabels: {
                display: true,
                anchor: 'end'
                ,align: 'start'
                ,color: 'white'
                ,backgroundColor: 'rgba(0, 0, 0, 0.5)'
                ,borderColor: 'white'
                ,borderWidth: 1
                ,borderRadius: 6
                ,font: {
                    size: 16,
                },
                formatter: function(value, context) {
                    return value;
                }
            }
      },
      scales:{
            x: {
                ticks: {
                    font: {
                        size: 16,
                        weight: 'bold'
                    }
                }
            },
            y: {
                ticks: {
                    font: {
                        size: 16,
                        weight: 'bold'
                    }
                }
            }
        },
      
    },
  };
  


new Chart(c[key]['element'], c[key]['config'])
</script>
