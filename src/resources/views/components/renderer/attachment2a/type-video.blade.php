{{-- @dump($video) --}}
<div class="h-full break-inside-avoid">
    <div data-id="{{$video['id']}}" name='{{$name}}' class="{{$thumbnailClass}} {{$video['borderColor']}}">
        @if($openType == '_blank') <a class="cursor-pointer" target="_blank" href="{{$path.$video['url_media']}}"> @endif
            @php
                $isMobile = MobileDetect::isMobile();
            @endphp
            <video 
                class="w-auto object-cover bg-slate-500 aspect-square" 
                src="{{$path.$video['url_media']}}" 
                alt="{{$video['filename']}}" 
                {{$isMobile ? "autoplay" : ""}}
                {{-- controls muted loop --}}
                >
                Browser does not support video tag.
            </video>
        @if($openType == '_blank') </a> @endif
        
        <x-renderer.attachment2a.comp-thumb-video-icon :attachment="$video" path="{{$path}}" />
        <x-renderer.attachment2a.comp-thumb-deleted-mark :attachment="$video" />
        <x-renderer.attachment2a.comp-thumb-black-layer 
            :attachment="$video" 
            name="{{$name}}" 
            readOnly="{{$readOnly}}" 
            destroyable="{{$destroyable}}"
            openType="{{$openType}}"
            path="{{$path}}"
            />
    </div>
    <x-renderer.attachment2a.comp-thumb-uploader 
        :attachment="$video" 
        hideUploader="{{$hideUploader}}" 
        hideUploadDate="{{$hideUploadDate}}" 
        />
</div>