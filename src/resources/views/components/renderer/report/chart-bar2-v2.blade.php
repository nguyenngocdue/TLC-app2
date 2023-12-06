
<div class="flex justify-center">
	<div class="block" title="{{$titleTooltip}} (Component Name: chart-bar2v2)">
		<canvas id="{{$key}}" width={{$dimensions['width'] ?? 400}} height={{$dimensions['height'] ?? 400}}></canvas>
	</div>
</div>

{{-- @dd($meta) --}}
 
@once
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-stacked100@1.0.0"></script>
<script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>

@endonce
<script>
var key = '{{$key}}';
var chartType = '{{$chart_type}}';
var chartData = {
    labels: {!! $meta['labels'] !!},
    numbers: {!! $meta['numbers'] !!},
};

var numColors = chartData.labels.length;
var generatedColors = generateColors(numColors);
//console.log(generatedColors);


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
        //show title on columns of chart
        //onHover: function(event, chart) {
        //    console.log('Hello');
        // },
        responsive: true,
        indexAxis: '{!! $dimensions['indexAxis'] ?? 'x' !!}',
        scales: {
                y: {
                    suggestedMax: {!! $dimensions['scaleMaxY'] ?? 'null' !!},
                    beginAtZero: true,
                    //max: {!! $dimensions['scaleMaxY'] ?? 'null' !!}, 
                    ticks: {
                        display: {!! json_encode($dimensions['displayTicksY'] ?? true) !!},
                        font: {
                            size:  {!! $dimensions['fontSizeAxisXY'] ?? 14 !!}, 
                            weight: 'bold'
                        }
                    },
                    title: {
                        position: 'top',
                        display: {!! $dimensions['displayTitleY'] ?? 1 !!},
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
                        display: {!! json_encode($dimensions['displayTicksX'] ?? true) !!},
                        font: {
                            size:  {!! $dimensions['fontSizeAxisXY'] ?? 14 !!}, 
                        },
                        // change legends on Y axis
                        //callback: function(value, index, ticks) {
                        //    return '$';
                        //}
                    },
                    title: {
                        display: {!! $dimensions['displayTitleX'] ?? 1 !!},
                        text: '{!! $dimensions['titleX'] ?? null !!}',
                        font: {
                            size: {!! $dimensions['fontSize'] ?? 14 !!}, 
                            weight: 'bold' 
                        }
                    },
                }
            },
        plugins: { 
            tooltip: {
                enabled: true,
                callbacks: {
                    label: function(context) {
                            var label = '{!! $dimensions['tooltipLabel'] ?? 'data' !!}'; 
                            var value =  context.raw;
                            return label + ': ' + value.toFixed(2);
                    }
                },
            },    
            title:{
                display: {!! $dimensions['displayTitleChart'] ?? 0 !!},
                text:  '{!! $dimensions['titleChart'] ?? null !!}',
                font:{
                    size: {!! $dimensions['fontSizeTitleChart'] ?? 16 !!}, 
                    weight: 'bold' 
                },
                position: '{!! $dimensions['positionTitleChart'] ?? 'bottom' !!}'
            },
            legend: {
                order: 1,
                position: 'bottom',
                display: {!! $dimensions['displayLegend'] ?? 0 !!},
                onClick: (evt, legendItem, legend) => {
                    const index = legend.chart.data.labels.indexOf(legendItem.text);
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
                display: {!! $dimensions['displayTitleOnTopCol'] ? 'function(context) {
                            return context.dataset.data[context.dataIndex] !== 0 
                                    && context.dataset.data[context.dataIndex] !== "" 
                                    && context.dataset.data[context.dataIndex] !== null;
                        }' : 'false' !!},
                anchor: 'end',
                align: '{!! $dimensions['dataLabelAlign'] ?? 'top' !!}',
                color: 'white',
                rotation: {!! json_encode($dimensions['rotation_datalabel'] ?? 0) !!},
                backgroundColor: 'rgba(0, 0, 0, 0.5)',
                borderColor: 'white',
                borderWidth: 0.5,
                borderRadius: 2.5,
                offset: '{!! $dimensions['dataLabelOffset'] ?? '0' !!}',
                formatter: function(value, context) {
            		return (value.toFixed(2))
				}
            },
            zoom: {
					zoom: {
					wheel: {
						enabled: {!! $dimensions['zoomWheelEnabled'] ?? 0 !!},
					},
					pinch: {
						enabled: {!! $dimensions['zoomPinchEnabled'] ?? 0 !!},
					},
					mode: '{!! $dimensions['zoomMode'] ?? 'xy' !!}',
					}
			},
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


    var datasets = chartConfig.data.datasets;
    var numCategories = datasets[0].data.length;
    var sumValues = new Array(numCategories).fill(0);

    // Iterate over each dataset and sum up the values for each category
    datasets.forEach(function (dataset) {
        dataset.data.forEach(function (value, index) {
            var  value = isNaN(parseFloat(value)) ? 0 : parseFloat(value);
            sumValues[index] += value;
        });
    });
    // Calculate the average for each category
    var average = sumValues.reduce((a, b) => a + b) / sumValues.length;

    var averageValues = sumValues.map(function (value) {
        return average;
    });
        // Add the average line dataset
    chartConfig.data.datasets.unshift({
        label: 'Average',
        type: 'line',
        yAxisID: 'y',
        backgroundColor: [],
        data: averageValues,
        borderColor: {!! json_encode($dimensions['color_average_line'] ?? '#FF0000')  !!},
        pointBackgroundColor: '#3498db', // Set the color of the points
        pointBorderColor: '#000080', // Set the color of the point borders
        fill: false,
        pointRadius: 1, // Set the point radius to 0 to hide points on the average line
        borderWidth: 2,
        // Adjust text and font
        text: 'Average Line',
        font: {
            size: 16,
            weight: 'bold',
            family: 'Arial', // Choose the desired font family
        },
        hidden: {!! json_encode($dimensions['hddien_average_line'] ?? false ? true : false)  !!},
        datalabels: {
            display:  {!! json_encode($dimensions['show_datalabel'] ?? true)  !!},
            borderWidth: 0,
            borderRadius: 0,
            rotation: 0,
            backgroundColor: null,
            color: 'black',
            align: 'end',
            anchor: 'end',
            offset: -4 ,
            font: {
                weight: 'bold',
            },
            formatter: function (value, context) {
                // Display data label only for the first column
                if (context.dataIndex === sumValues.length - 1) {
                    return '(' + value.toFixed(2) + ')';
                } else {
                    return null; // Hide data label for other columns
                }
            }
        }
    });

var chartElement = document.getElementById(key).getContext('2d');
var chartRendered = new Chart(chartElement, chartConfig);
</script>
