@foreach($allWidgetGroups as $widgetGroup)
<x-renderer.card title="{{$widgetGroup['title']}}">
    <div class="mb-8 grid gap-6 2xl:grid-cols-5 xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1">
        @foreach($widgetGroup['children'] as $widget)
        {{-- @dump($widget) --}}
        <x-renderer.card title="{{$widget['title_b']}}" tooltip="{{$widget['name']}}">
            <x-renderer.report.widget table="{{$table}}" projectId="{{$projectId}}" title="Total {{$widget['title_a']}}" figure="{{number_format( $widget['meta']['max']) }}" />
            <br />
            <x-renderer.report.chart key="{{md5($widget['title_a'].$widget['title_b'])}}" chartType="{{$widget['chart_type']}}" :meta="$widget['meta']" :metric="$widget['metric']" :widgetParams="$widget['params']" />
        </x-renderer.card>
        @endforeach
    </div>
</x-renderer.card>
@endforeach
