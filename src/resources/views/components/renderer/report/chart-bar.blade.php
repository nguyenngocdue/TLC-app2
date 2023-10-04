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
            backgroundColor: generatedColors
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
                    console.log(index);
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

                                    return {
                                        text: legendText,
                                        fillStyle: backgroundColor,
                                        strokeStyle: backgroundColor,
                                        hidden: visibility[index],
                                        index: 0
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
                offset: '{!! $dimensions['dataLabelOffset'] ?? '0' !!}'
            }
        }
    }
};
var chartElement = document.getElementById(key).getContext('2d');
new Chart(chartElement, chartConfig);
</script>
