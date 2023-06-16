
<x-renderer.card title="{{$title}}">
    <div class="grid {{$gridCss}} gap-4 flex-wrap">
        @foreach($dataSource as $value)
            @php
            $color = $value['color'];
            $colorIndex = $value['color_index'];
            $bgIndex = 1000 - $colorIndex;
            $bgColor = 'bg-'. $color . '-' . $colorIndex;
            $textColor = 'text-'.$color . '-' . $bgIndex;
            @endphp
        <div class="flex items-center col-span-1">
            <div class="border rounded dark:border-gray-600 h-8 w-8 {{$bgColor}} items-center p-1 mr-2 border-r text-center">
                {!!$value['icon'] ?? ''!!}
            </div>
            <span class="text-sm {{$textColor}}">{{$value['title']}}</span>
        </div>
        @endforeach
    </div>
</x-renderer.card>