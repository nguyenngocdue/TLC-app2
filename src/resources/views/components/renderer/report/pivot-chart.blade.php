    <x-renderer.report.chart 
            key="{{md5($widget['title_a'].$widget['title_b'])}}" 
            chartType="{{$widget['chart_type']}}" 
            :meta="$widget['meta']" :metric="$widget['metric']" 
            :widgetParams="$widget['params']" 
            showValue="{{$showValue ? 1: 0}}"  
            showDataLabel="{{$showDataLabel ? 1: 0}}"
            width={{$width}}
            height={{$height}}
            />
