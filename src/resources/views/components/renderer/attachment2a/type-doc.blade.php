{{-- @dump($doc) --}}
<div data-id="{{$doc['id']}}" name='{{$name}}' class="border rounded text-left px-2 my-0.5 hover:bg-blue-200 break-inside-avoid">
    <div class="items-center gap-2 p-1">
        <div class="flex gap-2 group relative items-center">
            <a href="{{$path.$doc['url_media']}}" 111111 target="_blank" class="text-blue-500 w-full text-md-vw text-left flex items-center">
                <x-renderer.attachment2a.comp-file-icon class="text-2xl fa-light mr-1" extension="{{$doc['extension'] ?? null}}" />
                {{$doc['filename']}}
            </a>
            <x-renderer.attachment2a.comp-doc-deleted-mark :attachment="$doc" />
            <x-renderer.attachment2a.comp-doc-trash-button :attachment="$doc" readOnly="{{$readOnly}}" destroyable="{{$destroyable}}" name="{{$name}}"/>
        </div>
        <x-renderer.attachment2a.comp-doc-uploader :attachment="$doc" hideUploader="{{$hideUploader}}" hideUploadDate="{{$hideUploadDate}}" />
    </div>
</div>