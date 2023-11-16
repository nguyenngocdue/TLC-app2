@php
$avatar = $avatar ? $avatar : "/images/avatar.jpg";
$bgGray = $gray ? "bg-gray-900 bg-opacity-30": "";
$textGray = $gray ? "text-gray-600" : "";
$class .= $href ? "hover:bg-gray-200 hover:cursor-pointer":"";
@endphp

@if($href)
<a href="{{$href}}" class="w-full">
@endif

@if($verticalLayout)
<div class="{{$bgGray}} {{$class}} min-h-[36px] rounded" title="{{$tooltip}}" component="avatar-item-vertical">
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
<div class="{{$bgGray}} {{$class}} min-h-[36px] flex items-center text-sm rounded px-1 w-full justify-end" title="{{$tooltip}}" component="avatar-item-flipped">
    <span>
        <p class="font-semibold text-right {{$textGray}}">{{$title}}</p>
        <p class="text-right text-xs text-gray-600 dark:text-gray-300 ">{{$description}}</p>
        <i>{{$content}}</i>
    </span>
    <div class="{{$sizeStr}} ml-2">
        <span class="relative hidden {{$sizeStr}} rounded-full sm:block">
            <img class="object-cover w-full h-full {{$shape}}" src="{{$avatar}}" loading="lazy">
        </span>
    </div>
</div>
@else
<div class="{{$bgGray}} {{$class}} min-h-[36px] flex items-center text-sm rounded px-1 w-full " title="{{$tooltip}}" component="avatar-item-normal">
    <div class="{{$sizeStr}} mr-2">
        <span class="relat1ive hidden {{$sizeStr}} rounded-full sm:block">
            <img class="object-cover w-full h-full {{$shape}}" src="{{$avatar}}" loading="lazy">
        </span>
    </div>
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