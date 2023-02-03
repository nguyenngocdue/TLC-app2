@if(strlen($slot)>0)
@php
$json = json_decode($slot);
foreach ( explode(",", $rendererParam) as $param) {
    $pairs = explode("=", $param);
    if(!$avatar || $pairs[0] !== 'avatar'){
        if($json) ${$pairs[0]} = ($json->{$pairs[1]} ?? "");
    }
}
@endphp
@endif

@php
$avatar = $avatar ? $avatar : "/images/avatar.jpg";
$bg_gray = $gray ? "bg-gray-300":"";
$txt_gray = $gray ? "text-gray-600" : "";
@endphp

<div class="flex items-center text-sm {{$bg_gray}} rounded px-1 w-full" component="avatar-name">
    <span class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
        <img class="object-cover w-full h-full rounded-full" src="{{$avatar}}" alt="" loading="lazy">
        <span class="absolute inset-0 rounded-full" aria-hidden="true"></span>
    </span>
    <span>
        @if($href)
        <a class="font-semibold text-left {{$txt_gray}}" href="{{$href}}">{{$title}}</a>
        @else
        <p class="font-semibold text-left {{$txt_gray}}">{{$title}}</p>
        @endif

        <p class="text-xs text-gray-600 dark:text-gray-400 ">
            {{$description}}
        </p>
    </span>
</div>
