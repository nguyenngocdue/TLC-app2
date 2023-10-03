@props(['chartType'])

<div class="block"><canvas id="{{$key}}"></canvas></div>

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
	var indexAxis = 'x'; // Giá trị mặc định

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
        scales: {
            y: {
                beginAtZero: true,
                max: {!! $dimensions['scaleMaxY'] ?? 'null' !!} 
                ,ticks: {
						font: {
                            size:  {!! $dimensions['fontSize'] ?? 14 !!} , 
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
                            size:  {!! $dimensions['fontSize'] ?? 14 !!} , 
                        }
				},
                title: {
                    display: true,
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
                labels: {
                    generateLabels: function(chart) {
                        return datasets.labels.map(function(label, index) {
                            return {
                                text: label,
                                fillStyle: datasets.backgroundColor[index],
                                hidden: false,
                                lineCap: 'round',
                                index: index,
                            };
                        });
                    }
                }
            },
			datalabels:{
				display: true
                ,anchor: 'end'
				,align: 'top'
				,color: 'white'
				,backgroundColor: 'rgba(0, 0, 0, 0.5)'
				,borderColor: 'white'
				,borderWidth: 1
				,borderRadius: 6
       		}
        }
    }
};

var chartElement = document.getElementById(key).getContext('2d');
var myChart = new Chart(chartElement, chartConfig);

// Thêm sự kiện click vào phần tử canvas để ẩn/hiện dữ liệu
document.getElementById('{{$key}}').addEventListener('click', function(event) {
    console.log('Click event fired!');
    var activePoints = myChart.getElementsAtEvent(event);
    var index = activePoints[0]?.index;

    if (index !== undefined) {
        var meta = myChart.getDatasetMeta(0);
        meta.data[index].hidden = !meta.data[index].hidden; // Đảo ngược trạng thái ẩn/hiện
        myChart.update();
    }
});
</script>

