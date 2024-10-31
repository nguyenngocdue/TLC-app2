
<script>
    function showModal(data) {
        const alpineRef = document.querySelector('[x-ref="alpineRef"]');
        const alpineComponent = alpineRef.__x.$data.toggleModal('modal-report-chart');
        const {tableName} = data;
        const url = "/api/v1/entity/"+tableName+"_renderTable";
        $.ajax({
            type:'POST',
            url,
            data,
            success: (response)=>{
                $("#divMain").html(response.hits)
            },
            error:(response)=>{
                // console.log(response.responseJSON.message)
                const message = response.responseJSON.message
                $("#divMain").html("<b class='text-red-500'>"+message+"</b>")
            }
        })
    }
</script>

<x-modals.modal-report-chart modalId="modal-report-chart"/>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="border-2 border-gray-600 p-1 {{$class}}">

    @if (isset($jsonOptions->libraryType) &&  strtolower($jsonOptions->libraryType) === 'chartjs')
        @php
            $height = $jsonOptions->options->height ?? 450;
        @endphp
        <canvas id="{{ $key }}" height={{$height}}></canvas>
    @else
        <div id="{{ $key }}"></div> 
    @endif
</div>

<script>
    var key = {!! json_encode($key) !!} ; 
    var options = {!! json_encode($jsonOptions) !!};

    var chartElement = document.querySelector("#" + CSS.escape(key));
    if (options?.libraryType?.toLowerCase() === 'chartjs') {
        var ctx = chartElement.getContext('2d');
        var myPieChart = new Chart(ctx, options);
    } else {
            // change formatter from string to JavaScript function 
        if (typeof options?.dataLabels?.formatter === 'string') {
            options.dataLabels.formatter = eval("(" + options.dataLabels.formatter + ")");
        }

        if (typeof options?.tooltip?.y?.formatter === 'string') {
            options.tooltip.y.formatter = eval("(" + options.tooltip.y.formatter + ")");
        }

        // add event handler from string to JavaScript function
        if (typeof options.chart?.events?.click === 'string') {

            options.chart.events.click = eval("(" + options.chart.events.click + ")");
        }

        // Hidden label of Y axis
        if (typeof options.yaxis?.labels?.formatter === 'string') {
            options.yaxis.labels.formatter = eval("(" + options.yaxis.labels.formatter + ")");
        }
        // Change Total's color 
        if(Array.isArray(options?.colors) && typeof options.colors[0] === 'string' && options.colors[0].trim().startsWith('function')) {
            options.colors[0] = eval("(" + options.colors[0] + ")")
        }
        var chart = new ApexCharts(chartElement, options);
        chart.render();
    }
</script>

