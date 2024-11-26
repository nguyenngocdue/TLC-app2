@php
    $height = $jsonOptions->options->height ?? 500;
    $width = $jsonOptions->options->width ?? null;
    $dimensions = $jsonOptions->dimension ?? null;
    $align = $dimensions ? $dimensions->align : 'flex justify-center'; 
@endphp
<div class="relative border-2 border-gray-600 p-4 {{$class}} {{$align}}">
    @if (isset($jsonOptions->libraryType) &&  strtolower($jsonOptions->libraryType) === 'chartjs')
        @once
            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>

            <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>

        @endonce
        <canvas id="{{ $key }}" height={{$height}} width={{$width ? $with.'px' : '100%'}}></canvas>
    @elseif(isset($jsonOptions->libraryType) &&  strtolower($jsonOptions->libraryType) === 'echart')
        @once
            <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
        @endonce
        @php
            $width = $dimensions?->width ? ($width ? $width.'px' : '100%'): '100%';
            $height = ($dimensions?->height ?? $height).'px';
        @endphp
        <div id="main" style="width: {{$width}}; height: {{$height}};"></div>
    @else
        @once
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        @endonce
        <div id="{{ $key }}" style="width: {{$width ? $width.'px' : '100%'}}"></div> 
    @endif
</div>

<script>
    var key = {!! json_encode($key) !!} ; 
    var optionCons = {!! json_encode($jsonOptions) !!};
    var rpId = {!! json_encode($reportId) !!};

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

        if (typeof optionCons?.options?.params === 'object') {
            optionCons.options.params['originalRp'] = rpId;
        }

        if (typeof optionCons?.options?.onClick === 'string') {
            optionCons.options.onClick = eval("(" + optionCons.options.onClick  + ")");
        }

        // set colors for columns
       if (Array.isArray(optionCons.data.datasets)) {
            const datasets = optionCons.data.datasets;
            if (Array.isArray(datasets)) {
                datasets.forEach((item) => {
                    if (typeof item.backgroundColor === "string") {
                        item.backgroundColor = new Function("context", item.backgroundColor.replace(/^function\(context\) \{/, '').replace(/\}$/, ''));
                    }
                });
            }
        }

        var chart = new Chart(ctx, optionCons);

    } 
    else if (optionCons?.libraryType?.toLowerCase() === 'echart'){
        var main = document.getElementById('main');
        var myChart2 = echarts.init(main, '');
        myChart2.setOption(optionCons);
    } 
    else {
        var options = optionCons;
            // change formatter from string to JavaScript function 
        if (typeof options?.dataLabels?.formatter === 'string') {
            options.dataLabels.formatter = eval("(" + options.dataLabels.formatter + ")");
        }

        if (typeof options?.tooltip?.y?.formatter === 'string') {
            options.tooltip.y.formatter = eval("(" + options.tooltip.y.formatter + ")");
        }

        // max value for yAxis
        if (typeof options?.yaxis?.max === 'string') {
            options.yaxis.max = eval("(" + options.yaxis.max + ")");
        }

        // categories of xAxis
        if (typeof options?.xaxis?.categories === 'string') {
            options.xaxis.categories = eval("(" + options.xaxis.categories + ")");
            console.log(options.xaxis.categories);
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

