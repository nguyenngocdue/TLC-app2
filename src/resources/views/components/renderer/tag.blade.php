@php
$color = $color ?? "green";
$colorIndex = $colorIndex ?? 200;
$colorIndexInverted = 1000 - $colorIndex;
$bg = "bg-{$color}-{$colorIndex}";
$text = "text-{$color}-{$colorIndexInverted}";
$bg_dark = "dark:bg-{$color}-{$colorIndexInverted}";
$text_dark = "dark:text-{$color}-{$colorIndex}";
@endphp

<span class="{{$bg}} {{$text}} {{$bg_dark}} {{$text_dark}} whitespace-nowrap rounded-full font-semibold text-xs m-1 px-2 py-1 leading-tight">
    {{ $slot }}
</span
