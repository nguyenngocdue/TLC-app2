<x-renderer.card title={{$titleLegend}}>
    <div class="grid grid-cols-{{$dataRender['col']}} gap-4 flex-wrap">
        @foreach($dataRender['status'] as $status => $value)
        <div class="flex items-center {{-- col-span-2 --}}">
            <div class="border dark:border-gray-600 h-8 w-8 {{$value['legend_color']}} items-center p-1 mr-2 border-r text-center">
                {!!$value['legend_icon'] ?? ''!!}
            </div>
            <span>{{$value['title']}}</span>
        </div>
        @endforeach
    </div>
</x-renderer.card>
