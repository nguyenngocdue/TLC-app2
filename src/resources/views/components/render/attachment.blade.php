@props(['attachment','model','relationship'])

@if (count($items) == 0)
    <span class="text-xs px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
        {{count($items)}} items
    </span>
@elseif (count($items) <= 3)
<div class="flex items-center text-sm">
    @foreach ($items as $item)
        @php
            $imageExtension = ['jpeg','png','jpg','gif','svg']    
        @endphp
        @if(in_array($item->extension,$imageExtension))
            @php
            $url_thumbnail = $path.$item->url_thumbnail;
            $url_media = $path.$item->url_media;
            @endphp
            <div class="relative hidden w-12 h-12 mr-3 md:block" title="{{$item->filename}}">
                <a href="{{$url_media}}">
                    <img class="object-cover w-full h-full" src="{{$url_thumbnail}}" alt="{{$item->filename}}"/>
                </a>
            </div>
        @else
        @php
        @endphp
            <div class="relative hidden w-12 h-12 mr-3 md:block" title="{{$item->filename}}">
                <a href="{{route('uploadfiles.download',$item->id)}}">
                    <img class="object-cover w-full h-full" src="{{asset('logo/file.png')}}" alt="{{$item->filename}}"/>
                </a>
            </div>
        @endif
    @endforeach
</div>
@elseif (count($items) > 3 )
    <div class="flex items-center text-sm">
        @foreach ($itemShows as $item)
            @php
                $imageExtension = ['jpeg','png','jpg','gif','svg']    
            @endphp
            @if(in_array($item->extension,$imageExtension))
                @php
                $url_thumbnail = $path.$item->url_thumbnail;
                $url_media = $path.$item->url_media;
                @endphp
                <div class="relative hidden w-12 h-12 mr-3 md:block" title="{{$item->filename}}">
                    <a href="{{$url_media}}">
                        <img class="object-cover w-full h-full" src="{{$url_thumbnail}}" alt="{{$item->filename}}"/>
                    </a>
                </div>
            @else
                <div class="relative hidden w-12 h-12 mr-3 md:block" title="{{$item->filename}}">
                    <a href="{{route('uploadfiles.download',$item->id)}}">
                        <img class="object-cover w-full h-full" src="{{asset('logo/file.png')}}" alt="{{$item->filename}}"/>
                    </a>
                </div>
            @endif
        @endforeach
        
    </div>
    @if (isset($countRemaining))
        <span class="text-xs px-2 py-1 mt-2 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
            {{$countRemaining}} more
        </span>
    @endif
@endif