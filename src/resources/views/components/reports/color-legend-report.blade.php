<x-renderer.card title={{$titleLegend}}>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 2xl:grid-cols-8 gap-4 flex-wrap">
        @foreach($dataRender['status'] as $status => $value)
        <div class="flex items-center col-span-1">
            <div class="border rounded dark:border-gray-600 h-8 w-8 {{$value['legend_color']}} items-center p-1 mr-2 border-r text-center">
                {!!$value['legend_icon'] ?? ''!!}
            </div>
            <span class="text-sm">{{$value['title']}}</span>
        </div>
        @endforeach
    </div>
</x-renderer.card>