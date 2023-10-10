@props(['chartType'])

<div class="block"><canvas id="{{$key}}" width={{$dimensions['width'] ?? null}} height={{$dimensions['height'] ?? null}}></canvas></div>

@once
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-stacked100@1.0.0"></script>
<script>
const COLORS = ['#4dc9f6','#f67019','#f53794','#537bc4','#acc236','#166a8f','#00a950','#58595b','#8549ba'];
</script>
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
Chart.register(ChartjsPluginStacked100.default);

var chartConfig = {
    type: chartType,
    data: {
        labels: chartData.labels,
        datasets: [{
            label: "data",
            data: chartData.numbers,
            backgroundColor: generatedColors,
            barPercentage:  {!! $dimensions['barPercentage'] ?? 1 !!},
        }]
    },
    options: {
        responsive: true,
        indexAxis: '{!! $dimensions['indexAxis'] ?? 'x' !!}',
        scales: {
            y: {
                beginAtZero: true,
                max: {!! $dimensions['scaleMaxY'] ?? 'null' !!}, 
                ticks: {
                    font: {
                        size:  {!! $dimensions['fontSizeAxisXY'] ?? 14 !!}, 
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
                    }
                }
            },
            x: {
                max: {!! $dimensions['scaleMaxX'] ?? 'null' !!}, 
                ticks: {
                    font: {
                        size:  {!! $dimensions['fontSizeAxisXY'] ?? 14 !!}, 
                    },
                    // change legends on Y axis
                    //callback: function(value, index, ticks) {
                    //    return '$' + ticks;
                    //}
                },
                title: {
                    display: true,
                    text: '{!! $dimensions['titleX'] ?? null !!}',
                    font: {
                        size: {!! $dimensions['fontSize'] ?? 14 !!}, 
                        weight: 'bold' 
                    }
                },
            }
        },
        plugins: {
            title:{
                display: {!! $dimensions['displayTitleChart'] ?? 0 !!},
                text:  '{!! $dimensions['titleChart'] ?? null !!}',
                font:{
                    size: {!! $dimensions['fontSizeTitleChart'] ?? 16 !!}, 
                    weight: 'bold' 
                }
            },
            legend: {
                position: 'bottom',
                display: {!! $dimensions['displayLegend'] ?? 0 !!},
                onClick: (evt, legendItem, legend) => {
                    const index = legend.chart.data.labels.indexOf(legendItem.text);
                    console.log(legendItem);
                    legend.chart.toggleDataVisibility(index);
                    legend.chart.update();
                },
                labels: {
                        boxWidth: 40,
                        generateLabels: function(chart) {
                            const data = chart.data;
                            let visibility = [];
                            for(let i = 0; i < data.labels.length; i++) {
									if(chart.getDataVisibility(i) === true){
										visibility.push(false);
									} else{
										visibility.push(true)
									}
								};
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map(function(label, index) {
                                    const dataset = data.datasets[0];
                                    const backgroundColor = dataset.backgroundColor[index];
                                    //  legend modified here
                                    const legendText = `${label}`;
                                    //console.log(visibility[index]);

                                    return {
                                        text: legendText,
                                        fillStyle: backgroundColor,
                                        strokeStyle: backgroundColor,
                                        hidden: visibility[index],
                                        //index: 0
                                    };
                                });
                            }
                            return [];
                        }
                    }

            },
            datalabels: {
                display: function(context) {
                    return context.dataset.data[context.dataIndex] !== 0 
                            && context.dataset.data[context.dataIndex] !== "" 
                            && context.dataset.data[context.dataIndex] !== null;
                },
                anchor: 'end',
                align: '{!! $dimensions['dataLabelAlign'] ?? 'top' !!}',
                color: 'white',
                backgroundColor: 'rgba(0, 0, 0, 0.5)',
                borderColor: 'white',
                borderWidth: 1,
                borderRadius: 6,
                offset: '{!! $dimensions['dataLabelOffset'] ?? '0' !!}',
                formatter: function(value, context) {
            		return (value.toFixed(2))
				}
            }
        }
    },
      scales: {
        x: {
            barPercentage: 0.2, // Kích thước của các cột chỉ chiếm 70% chiều rộng của không gian giữa chúng
        },
        y: {
            beginAtZero: true
        }
    }
};
var chartElement = document.getElementById(key).getContext('2d');
var chartRendered = new Chart(chartElement, chartConfig);
//chartRendered.data.datasets[0].barThickness = {!! $dimensions['widthBar'] ?? null !!};
//chartRendered.update();
//console.log(chartRendered)
</script>
