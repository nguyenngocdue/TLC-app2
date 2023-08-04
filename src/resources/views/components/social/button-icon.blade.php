<div class="{{$width}} flex space-x-2 justify-center items-center hover:bg-gray-100 dark:hover:bg-gray-800 text-base py-2 rounded-lg cursor-pointer {{$textColor}}">
    @if($src)
    <img src="{{$src}}" alt="Photos" class="{{$widthIcon}} {{$heightIcon}} rounded-full cursor-pointer">
    @endif
    @if($icon)
        <i class="{{$widthIcon}} {{$heightIcon}} {{$icon}} rounded-full cursor-pointer"></i>
    @endif
    <span className="text-xs font-semibold text-gray-500 dark:text-white">
        {{$title}}
    </span>
</div>