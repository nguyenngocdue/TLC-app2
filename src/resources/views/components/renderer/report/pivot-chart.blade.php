{{-- <x-renderer.card title="{{$widget['title_b']}}" tooltip="{{$widget['name']}}"> --}}
    <x-renderer.report.chart key="{{md5($widget['title_a'].$widget['title_b'])}}" chartType="{{$widget['chartType']}}" :meta="$widget['meta']" :metric="$widget['metric']" :widgetParams="$widget['params']" showValue="{{$showValue}}" />
{{-- </x-renderer.card> --}}
