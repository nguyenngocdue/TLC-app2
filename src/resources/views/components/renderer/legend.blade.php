
<x-renderer.card title="{{$title}}">
    <div class="grid {{$gridCss}} gap-4 flex-wrap">
        @if(!empty($dataSource))
            @foreach($dataSource as $value)
                @php
                $bgColor = 'bg-'. $value['bg_color'];
                $textColor = 'text-'.$value['text_color'];
                $tooltip = $value['action-buttons']['tooltip'] ?? '';
                $statusTitle = ($value['action-buttons']['status_title'] ?? $value['title']) ?: $value['title'];
                @endphp
                <div class="flex {{-- items-baseline --}} col-span-1 items-center">
                    <div class="border roun1ded dark:border-gray-600 h-8 w-8 {{$bgColor}} {{$textColor}} items-center p-1 mr-2 border-r text-center">
                        {!!$value['icon'] ?? ''!!}
                    </div>
                    <div>
                        <span class="text-sm {{$textColor}}" title="{{$value['name']}}">{{$statusTitle}}</span>
                        <div class="italic text-xs {{$textColor}}">{{$tooltip}}</div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    {{$slot}}
</x-renderer.card>