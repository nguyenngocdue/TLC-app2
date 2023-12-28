<x-renderer.card title="{{$title}}">
    <div class="grid {{$gridCss}} gap-4 flex-wrap">
        @if(!empty($dataSource))
            @php
            $textColor = 'text-black';
            @endphp
            @foreach($dataSource as $value)
                @php
                $bgColor = 'bg-'. $value['color'].'-400';
                @endphp
                <div class="flex items-center col-span-1">
                    <div class="border roun1ded dark:border-gray-600 h-8 w-8 {{$bgColor}} {{$textColor}} items-center p-1 mr-2 border-r text-center">
                        {!!$value['icon'] ?? ''!!}
                    </div>
                    <span class="text-sm {{$textColor}}" title="{{$value['name']}}">{{$value['name']}}</span>
                </div>
            @endforeach
        @endif
    </div>
</x-renderer.card>