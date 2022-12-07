@if(strlen($slot)>0)

@php
$json = json_decode($slot);
foreach ( explode(",", $rendererParam) as $param) {
    $pairs = explode("=", $param);
    if($json) ${$pairs[0]} = $json->{$pairs[1]} ?? "";
}
$avatar = $avatar ? $avatar : "https://cdn.vectorstock.com/i/1000x1000/23/70/man-avatar-icon-flat-vector-19152370.webp";
@endphp

<div class="flex items-center text-sm">
    <span class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
        <img class="object-cover w-full h-full rounded-full" src={{$avatar}} alt="" loading="lazy">
        <span class="absolute inset-0 rounded-full" aria-hidden="true"></span>
    </span>
    <span>
        @if($href)
        <a href="{{$href}}" class="font-semibold">{{$title}}</a>
        @else
        <p class="font-semibold">{{$title}}</p>
        @endif

        <p class="text-xs text-gray-600 dark:text-gray-400">
            {{$description}}
        </p>
    </span>
</div>

@endif