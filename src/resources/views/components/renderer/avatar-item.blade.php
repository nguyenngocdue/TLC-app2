@php
$avatar = $avatar ? $avatar : "/images/avatar.jpg";
$bgGray = $gray ? "bg-gray-300": "";
$textGray = $gray ? "text-gray-600" : "";
$class .= $href ? "hover:bg-gray-200 hover:cursor-pointer":"";
@endphp

@if($href)
<a href="{{$href}}" class="w-full">
@endif

@if($verticalLayout)
<div class="{{$bgGray}} {{$class}} rounded" title="{{$tooltip}}" component="avatar-item-vertical">
    <div class="flex justify-center">
        <div class="{{$sizeStr}}">
            <img class="object-cover w-full h-full {{$shape}}" src="{{$avatar}}" loading="lazy">
        </div>
    </div>
    <div class="flex text-xs justify-center">
        <span>
            <p class="text-xs font-semibold text-center {{$textGray}}">{{$title}}</p>
            <p class="text-xs text-gray-600 dark:text-gray-300 italic text-center">{{$description}}</p>
            <i>{{$content}}</i>
        </span>
    </div> 
</div>
@elseif($flipped)
<div class="{{$bgGray}} {{$class}} flex justify-end text-sm rounded px-1 w-full " title="{{$tooltip}}" component="avatar-item-flipped">
    <span class="mr-2">
        <p class="font-semibold text-right {{$textGray}}">{{$title}}</p>
        <p class="text-right text-xs text-gray-600 dark:text-gray-300 ">{{$description}}</p>
        <i>{{$content}}</i>
    </span>
    <span class="relative hidden {{$sizeStr}} rounded-full md:block">
        <img class="object-cover w-full h-full {{$shape}}" src="{{$avatar}}" loading="lazy">
        {{-- <span class="absolute inset-0 rounded-full" aria-hidden="true"></span> --}}
    </span>
</div>
@else
<div class="{{$bgGray}} {{$class}} flex items-center text-sm rounded px-1 w-full " title="{{$tooltip}}" component="avatar-item-normal">
    <span class="relative hidden {{$sizeStr}} mr-3 rounded-full md:block">
        <img class="object-cover w-full h-full {{$shape}}" src="{{$avatar}}" loading="lazy">
        {{-- <span class="absolute inset-0 rounded-full" aria-hidden="true"></span> --}}
    </span>
    <span>
        <p class="font-semibold text-left {{$textGray}}">{{$title}}</p>
        <p class="text-left text-xs text-gray-600 dark:text-gray-300 ">{{$description}}</p>
        <i>{{$content}}</i>
    </span>
</div>
@endif

@if($href)
</a>
@endif