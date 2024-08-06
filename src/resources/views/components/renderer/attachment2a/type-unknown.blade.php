{{-- @dump($unknown) --}}
<div data-id="{{$unknown['id']}}" name='{{$name}}' class="bg-red-300 border rounded text-left px-2 my-0.5 hover:bg-blue-200 break-inside-avoid">
    <div class="items-center gap-2 p-1">
        <div class="flex gap-2 group relative items-center">
            <a href="{{$path.$unknown['url_media']}}" 222222 target="_blank" class="text-red-800 w-full text-md-vw text-left flex items-center">
                <span class="px-1">This is an unsupported file, it could harm your computer:</span>
                <x-renderer.attachment2a.comp-file-icon class="text-2xl fa-light mr-1" extension="{{$unknown['extension'] ?? null}}" />
                {{$unknown['filename']}}
            </a>
            <x-renderer.attachment2a.comp-doc-deleted-mark :attachment="$unknown" />
            <x-renderer.attachment2a.comp-doc-trash-button :attachment="$unknown" readOnly="{{$readOnly}}" destroyable="{{$destroyable}}" name="{{$name}}"/>
        </div>
        <x-renderer.attachment2a.comp-doc-uploader :attachment="$unknown" hideUploader="{{$hideUploader}}" hideUploadDate="{{$hideUploadDate}}" />
    </div>
</div>