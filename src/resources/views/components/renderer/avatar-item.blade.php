@php
$avatar = $avatar ? $avatar : "/images/avatar.jpg";
$bgGray = $gray ? "bg-gray-300": "";
$textGray = $gray ? "text-gray-600" : "";
@endphp

<div class="flex items-center text-sm {{$bgGray}} rounded px-1 w-full" component="avatar-item">
    <span class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
        <img class="object-cover w-full h-full rounded-full" src="{{$avatar}}" alt="" loading="lazy">
        <span class="absolute inset-0 rounded-full" aria-hidden="true"></span>
    </span>
    <span>
        @if($href)
        <a class="font-semibold text-left {{$textGray}}" href="{{$href}}">{{$title}}</a>
        @else
        <p class="font-semibold text-left {{$textGray}}">{{$title}}</p>
        @endif

        <p class="text-xs text-gray-600 dark:text-gray-300 ">
            {{$description}}
        </p>
    </span>
</div>