@props(['chartType'])

<div class="block"><canvas id="{{$key}}" width={{$dimensions['width'] ?? null}} height={{$dimensions['height'] ?? null}}></canvas></div>

{{-- @dump($meta) --}}
{{-- @dump($metric) --}}
{{-- @dump($chartType) --}}
{{-- @dump($titleChart) --}}
{{-- @dump($dimensions) --}}

@once
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const COLORS = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba'];
</script>
@endonce
<script>
	var key = '{{$key}}';
	var chartType = '{{$chartType}}';
	var datasets = {
		labels: {!! $meta['labels'] !!},
		numbers: {!! $meta['numbers'] !!},
		backgroundColor: Object.values(COLORS)
	};

Chart.register(ChartDataLabels);

var chartConfig = {
    type: chartType,
    data: {
        labels: datasets.labels,
        datasets: [{
            label: "data",
            data: datasets.numbers,
            backgroundColor: datasets.backgroundColor
        }]
    },
    options: {
        indexAxis: '{!! $dimensions['indexAxis'] ?? 'x' !!}',
        scales: {
            y: {
                beginAtZero: true,
                max: {!! $dimensions['scaleMaxY'] ?? 'null' !!} 
                ,ticks: {
						font: {
                            size:  {!! $dimensions['fontSizeAxisXY'] ?? 14 !!} , 
                            weight: 'bold'
                        }
				},
                title: {
                    position: 'top',
                    display: true,
                    text: '{!! $dimensions['titleY'] ?? null !!}', 
                    font: {
                        size: {!! $dimensions['fontSize'] ?? 14 !!}, 
                        weight: 'bold' 
                    },
                }
            },
            x: {
                ticks: {
						font: {
                            size:  {!! $dimensions['fontSizeAxisXY'] ?? 14 !!} , 
                        }
				},
                title: {
                    display: true,
                    text: '{!! $dimensions['titleX'] ?? null !!}',
                    font: {
                        size: {!! $dimensions['fontSize'] ?? 14 !!}, 
                        weight: 'bold' 
                    }
                }
            }

        },
        plugins: {
            legend: {
                position: 'bottom',
                display: true,
                onClick: (evt, legendItem, legend) => {
                    const index = legend.chart.data.labels.indexOf(legendItem.text);
                    legend.chart.toggleDataVisibility(index);
                    legend.chart.update();
                },
                labels: {
                    generateLabels: function(chart) {
                        let visibility = [];
                        for(let i = 0; i < chart.data.labels.length; i++) {
                            if(chart.getDataVisibility(i) === true){
                                visibility.push(false);
                            } else{
                                visibility.push(true)
                            }
                        };
                        return datasets.labels.map(function(label, index) {
                            return {
                                text: label,
                                fillStyle: datasets.backgroundColor[index],
                                hidden: visibility[index],
                                lineCap: 'round',
                                index: index,
                            };
                        });
                    }
                },
               
                
            },
			datalabels:{
				display: function(context) {
				    return context.dataset.data[context.dataIndex] !== 0 && context.dataset.data[context.dataIndex] !== null;
				}
                ,anchor: 'end'
				,align: '{!! $dimensions['dataLabelAlign'] ?? 'top' !!}'
				,color: 'white'
				,backgroundColor: 'rgba(0, 0, 0, 0.5)'
				,borderColor: 'white'
				,borderWidth: 1
				,borderRadius: 6
                ,offset: {!! $dimensions['dataLabelOffset'] ?? null !!}
                
       		}
        },
    },
};

var chartElement = document.getElementById(key).getContext('2d');
var myChart = new Chart(chartElement, chartConfig);


</script>

