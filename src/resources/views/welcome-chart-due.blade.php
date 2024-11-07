@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

@php 
    $currentDate = str_replace('Nguyen', 'Mr.', 'Nguyen Ngoc Due');
    $fromDate = new DateTime('2024-09-18 08:58:59');
    $fromDate = $fromDate->format('Y-m-d');
@endphp

@php
    $entityType = '1a';
@endphp

{{-- Load Chart.js and plugins --}}
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>

<div class="w-1/2">
    <canvas id="reworkChart" width="800" height="400"></canvas>
</div>



@php
 $months = [1,2,3,4,5,6,7];
@endphp

{{-- <script>
    const ctx = document.getElementById('reworkChart').getContext('2d');
    var months = {!! json_encode($months) !!};

   
    const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
    const data = {
        labels: labels,
        datasets: [
            {
                label: 'Dataset 1',
                data: [12, 19, 3, 5, 2, 3, 7],
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
            },
            {
                label: 'Dataset 2',
                data: [8, 11, 13, 7, 10, 6, 9],
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
            },
            {
                label: 'Dataset 3',
                data: [4, 5, 6, 3, 2, 5, 8],
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
            },
        ]
    };

   
    const config = {
        type: 'bar',
        data: data,
        options: {
            params : {
                popupReportId: 67,
                labelIds : months,
                dataIndexLabel : "month",
                datasetVariable : "rework_type"
            },

            plugins: {
                title: {
                    display: true,
                    text: 'Chart.js Bar Chart - Stacked'
                }
            },
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true
                }
            },
            onClick : function (event, elements, configs) {
                    const [clickedElement] = elements;
                    if (!clickedElement) return; 
                    const { index: dataPointIndex, datasetIndex } = clickedElement; 
                    const {popupReportId, labelIds, dataIndexLabel, datasetVariable } = configs.config._config.options.params; 
                    const labelIdForDataPoint = labelIds[dataPointIndex]; 
                    const { datasets } = configs.config._config.data;
                    const selectedDatasetLabel = datasets[datasetIndex].label;
                    const requestParams = { popupReportId: popupReportId, labelId: labelIdForDataPoint, dataIndexLabel: dataIndexLabel, datasetVariable: datasetVariable, [datasetVariable] : selectedDatasetLabel};
                    console.log(requestParams);

                    showModal(requestParams) ;       
                }
        }
    };

    new Chart(ctx, config);
</script> --}}

{{-- <script>
    function generateCategories(){
        return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div id="chart"></div>
    
<script>
  var options = {
    series: [{
      name: 'Sales',
      data: [450, 650, 850, 400, 0, 700, 900] 
    },
    {
      name: 'Sales2',
      data: [450, 650, 850, 400, 550, 700, 800] 
    }
    ],
    chart: {
      type: 'bar',
      height: 350
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '55%',
        endingShape: 'rounded'
      }
    },
    dataLabels: {
      enabled: false
    },
    yaxis : {
        max: function(){
            const allDataPoints = options.series.flatMap(series => series.data);
            return Math.max(...allDataPoints) * 1.1;
        }
    },
    xaxis: {
      categories:generateCategories(), 
    },
    title: {
      text: 'Monthly Sales Data',
      align: 'center',
      style: {
        fontSize: '20px'
      }
    },
    colors: ['#00E396', '#3969FC'] 
  };

  
  var chart = new ApexCharts(document.querySelector("#chart"), options);
  chart.render();
</script>
 --}}

<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>

<!-- Thẻ div để chứa biểu đồ -->
<div id="main" style="width: 600px; height: 400px;"></div>

<script type="text/javascript">
    // Khởi tạo một instance của echarts dựa trên thẻ div đã chuẩn bị
    var myChart2 = echarts.init(document.getElementById('main'));

    // Cấu hình và dữ liệu cho biểu đồ
    var option = {
        title: {
            text: 'ECharts Example with Toolbox'
        },
        tooltip: {},
        legend: {
            data: ['sales']
        },
        toolbox: {
            show: true,
            feature: {
                saveAsImage: {
                    show: true, // Cho phép lưu biểu đồ dưới dạng hình ảnh
                    title: 'Save as Image'
                },
                dataView: {
                    show: true,
                    readOnly: false, // Cho phép người dùng xem và chỉnh sửa dữ liệu
                    title: 'Data View'
                },
                restore: {
                    show: true, // Cho phép khôi phục lại biểu đồ
                    title: 'Restore'
                },
                magicType: {
                    show: true, // Cho phép thay đổi loại biểu đồ
                    type: ['line', 'bar'], // Các loại biểu đồ có thể chuyển đổi
                    title: {
                        line: 'Switch to Line Chart',
                        bar: 'Switch to Bar Chart'
                    }
                },
                dataZoom: {
                    show: true, // Cho phép phóng to/thu nhỏ
                    title: {
                        zoom: 'Zoom In',
                        back: 'Zoom Out'
                    }
                }
            }
        },
        xAxis: {
            data: ['Shirts', 'Cardigans', 'Chiffons', 'Pants', 'Heels', 'Socks']
        },
        yAxis: {},
        series: [
            {
                name: 'sales',
                type: 'bar',  // Biểu đồ dạng bar
                data: [5, 20, 36, 10, 10, 20] // Dữ liệu bán hàng
            }
        ]
    };

    // Hiển thị biểu đồ bằng cấu hình đã chỉ định
    myChart2.setOption(option);
</script>





@endsection
