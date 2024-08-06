<div class="invisible flex justify-center hover:bg-[#00000080] group-hover/item:visible before:absolute before:-inset-1  before:bg-[#00000080]">
    <a title="{{$attachment['filename']}}" 
        @if($attachment['onClick'])
            onclick="{{$attachment['onClick']}}"
        @else
            href="{{$path . $attachment['url_media']}}"
            target='_blank' 
        @endif
        class="cursor-pointer hover:underline  text-white hover:text-blue-300 px-2 absolute top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] text-lg text-center w-full"
        >
        <span class="text-sm">{{$attachment['filename']}}</span>
    </a>

    <x-renderer.attachment2a.comp-thumb-trash-button readOnly="{{$readOnly}}" destroyable="{{$destroyable}}" :attachment="$attachment" name="{{$name}}"/>
</div>