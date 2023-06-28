@php
$avatar = $avatar ? $avatar : "/images/avatar.jpg";
$bgGray = $gray ? "bg-gray-300": "";
$textGray = $gray ? "text-gray-600" : "";
@endphp

@if($verticalLayout)
<div title="{{$tooltip}}" class="{{$bgGray}}" component="avatar-item">
    <div class="flex justify-center">
        <div class="w-12 h-12">
            <img class="object-cover w-full h-full rounded-full" src="{{$avatar}}" alt="" loading="lazy">
        </div>
        
    </div>
    <div class="flex text-xs justify-center">
        <span>
            @if($href)
            <a class="font-semibold text-center {{$textGray}}" href="{{$href}}">{{$title}}</a>
            @else
            <p class="text-xs font-semibold text-center {{$textGray}}">{{$title}}</p>
            @endif
            <p class="text-xs text-gray-600 dark:text-gray-300 italic text-center">
                {{$description}}
            </p>
        </span>
    </div> 
</div>
@elseif($flipped)
<div class="flex justify-end text-sm {{$bgGray}} rounded px-1 w-full" title="{{$tooltip}}" component="avatar-item">
    <span class="mr-2">
        @if($href)
        <a class="font-semibold text-right {{$textGray}}" href="{{$href}}">{{$title}}</a>
        @else
        <p class="font-semibold text-right {{$textGray}}">{{$title}}</p>
        @endif
        
        <p class="text-xs text-gray-600 dark:text-gray-300 ">
            {{$description}}
        </p>
    </span>
    <span class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
        <img class="object-cover w-full h-full rounded-full" src="{{$avatar}}" alt="" loading="lazy">
        <span class="absolute inset-0 rounded-full" aria-hidden="true"></span>
    </span>
</div>
@else
<div class="flex items-center text-sm {{$bgGray}} rounded px-1 w-full" title="{{$tooltip}}" component="avatar-item">
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
@endif
