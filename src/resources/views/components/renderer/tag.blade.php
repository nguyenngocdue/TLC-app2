@php
$color = $color ?? "green";
$colorIndex = $colorIndex ?? 200;
$colorIndexInverted = 1000 - $colorIndex;
$bg = "bg-{$color}-{$colorIndex}";
$text = "text-{$color}-{$colorIndexInverted}";
$bg_dark = "dark:bg-{$color}-{$colorIndexInverted}";
$text_dark = "dark:text-{$color}-{$colorIndex}";

$title_rendered = isset($title) ? "title='$title'" : "";
$class = $class ?? "";
@endphp

<span {!! $title_rendered !!} class="{{$bg}} {{$text}} {{$bg_dark}} {{$text_dark}} whitespace-nowrap rounded-lg font-semibold text-xs px-2 py-1 leading-tight {{$class}}">
    {{ $slot }}
</span
