@php
$color = $color ?? "green";
$bg = "bg-{$color}-100";
$text = "text-{$color}-700";
$bg_dark = "dark:bg-{$color}-700";
$text_dark = "dark:text-{$color}-100";
@endphp

<span class="{{$bg}} {{$text}} {{$bg_dark}} {{$text_dark}} whitespace-nowrap rounded-full font-semibold text-xs m-1 px-2 py-1 leading-tight">
    {{ $slot }}
</span
