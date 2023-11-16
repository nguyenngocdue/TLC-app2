
<x-renderer.card title="{{$title}}">
    <div class="grid {{$gridCss}} gap-4 flex-wrap">
        @if(!empty($dataSource))
            @foreach($dataSource as $value)
                @php
                $bgColor = 'bg-'. $value['bg_color'];
                $textColor = 'text-'.$value['text_color'];
                @endphp
                <div class="flex items-center col-span-1">
                    <div class="border roun1ded dark:border-gray-600 h-8 w-8 {{$bgColor}} {{$textColor}} items-center p-1 mr-2 border-r text-center">
                        {!!$value['icon'] ?? ''!!}
                    </div>
                    <span class="text-sm {{$textColor}}" title="{{$value['name']}}">{{$value['title']}}</span>
                </div>
            @endforeach
        @endif
    </div>
</x-renderer.card>