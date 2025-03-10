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
    <div class="flex text-xs-vw justify-center">
        <span>
            <p class="text-xs-vw font-semibold text-center {{$textGray}}">{{$title}}</p>
            <p class="text-xs-vw text-gray-600 dark:text-gray-300 italic text-center">{{$description}}</p>
            <i>{{$content}}</i>
        </span>
    </div> 
</div>
@elseif($flipped)
<div class="{{$bgGray}} {{$class}} min-h-[36px] flex items-center text-sm text-sm-vw rounded px-1 w-full justify-end" title="{{$tooltip}}" component="avatar-item-flipped">
    <span>
        <p class="font-semibold text-right {{$textGray}}">{{$title}}</p>
        <p class="text-right text-xs-vw text-gray-600 dark:text-gray-300 ">{!! $description !!}</p>
        <p class="text-right text-xs-vw italic">{{$content}}</p>
    </span>
    <div class="{{$sizeStr}} ml-2">
        <span class="relative  {{$sizeStr}} rounded-full sm:block1 hidden1">
            <img class="object-cover w-full h-full {{$shape}}" src="{{$avatar}}" loading="lazy">
        </span>
    </div>
</div>
@else
<div class="{{$bgGray}} {{$class}} min-h-[36px] flex items-center text-sm text-sm-vw rounded px-1 w-full " title="{{$tooltip}}" component="avatar-item-normal">
    <div class="{{$sizeStr}} mr-2">
        <span class="relat1ive  {{$sizeStr}} rounded-full sm:block1 hidden1">
            <img class="object-cover w-full h-full {{$shape}}" src="{{$avatar}}" loading="lazy">
        </span>
    </div>
    <span>
        <p class="font-semibold text-left {{$textGray}}">{{$title}}</p>
        <p class="text-left text-xs-vw text-gray-600 dark:text-gray-300 ">{!! $description !!}</p>
        <i>{{$content}}</i>
    </span>
</div>
@endif

@if($href)
</a>
@endif