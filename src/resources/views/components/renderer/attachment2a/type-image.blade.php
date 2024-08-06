{{-- @dump($image) --}}
<div class="h-full break-inside-avoid">
    <div data-id="{{$image['id']}}" name='{{$name}}' class="{{$thumbnailClass}} {{$image['borderColor']}}">
        @if($openType == '_blank') <a class="cursor-pointer w-full" target="_blank" href="{{$path.$image['url_media']}}"> @endif
            <img class="w-full" src="{{$path.$image['url_thumbnail']}}" alt="{{$image['filename']}}" />
        @if($openType == '_blank') </a> @endif
        <x-renderer.attachment2a.comp-thumb-deleted-mark :attachment="$image" />
        <x-renderer.attachment2a.comp-thumb-black-layer :attachment="$image" 
            name="{{$name}}" 
            readOnly="{{$readOnly}}" 
            destroyable="{{$destroyable}}"
            openType="{{$openType}}"
            path="{{$path}}"
            />
    </div>
    @if($image['showUrl']) 
    <div class="w-full flex justify-center">
        <a class="text-blue-600" target="_blank" href="{{$path.$image['url_media']}}">Full URL</a>
    </div>
    @endif
    <x-renderer.attachment2a.comp-thumb-uploader 
        :attachment="$image" 
        hideUploader="{{$hideUploader}}" 
        hideUploadDate="{{$hideUploadDate}}" 
        />
</div>