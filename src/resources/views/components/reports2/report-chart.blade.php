
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

<div class="border-2 border-gray-600 p-1 {{$class}}">

    @if (isset($jsonOptions->libraryType) &&  strtolower($jsonOptions->libraryType) === 'chartjs')
        @once
            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>

            {{-- <script src="path/to/chartjs/dist/chart.min.js"></script> --}}
            <script src="path/to/chartjs-plugin-zoom/dist/chartjs-plugin-zoom.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>

        @endonce
        @php
            $height = $jsonOptions->options->height ?? 400;
            $width = $jsonOptions->options->width ?? 400;
        @endphp
        <canvas id="{{ $key }}" height={{$height}} width={{$width}}></canvas>
    @else
        @once
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        @endonce
        <div id="{{ $key }}"></div> 
    @endif
</div>

<script>
    var key = {!! json_encode($key) !!} ; 
    var optionCons = {!! json_encode($jsonOptions) !!};

    var chartElement = document.querySelector("#" + CSS.escape(key));
    if (optionCons?.libraryType?.toLowerCase() === 'chartjs') {

        Chart.register(ChartDataLabels);
        
        var ctx = chartElement.getContext('2d');

        if (typeof optionCons?.options?.plugins?.datalabels?.formatter === 'string') {
            optionCons.options.plugins.datalabels.formatter = eval("(" + optionCons.options.plugins.datalabels.formatter + ")");
        }
        if (typeof optionCons?.options?.plugins?.tooltip?.callbacks?.label === 'string') {
            optionCons.options.plugins.tooltip.callbacks.label = eval("(" + optionCons.options.plugins.tooltip.callbacks.label + ")");
        }

        if (typeof optionCons?.options?.onClick === 'string') {
            optionCons.options.onClick = eval("(" + optionCons.options.onClick  + ")");
        }


        var myPieChart = new Chart(ctx, optionCons);
    } else {
        var options = optionCons;
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

