{{-- @dump($groupId) --}}
<div class="flex flex-col w-full">
    @if(!sizeof($attachments)) 
        <x-renderer.emptiness p="2" class="border hidden sm:block" message="There is no attachment to be found." />
        <x-renderer.emptiness p="2" class="border sm:hidden" message="File not found." />
    @else
        @foreach($attachments as $attachment)
            @if($attachment['isOrphan'])
                <input name="{{$name}}[toBeAttached][]" 
                        value="{{$attachment['id']}}" 
                        type="{{$hiddenOrText}}" 
                        placeholder="To be attached orphan IDs"
                        />
            @endif
        @endforeach

        @if(sizeof($images))
            <div class="{{$gridCols}} sm:p-1">
                @foreach($images as $image)
                    <x-renderer.attachment2a.type-image openType="{{$openType}}" readOnly="{{$readOnly}}" destroyable="{{$destroyable}}" path="{{$path}}" name="{{$name}}" hiddenOrText="{{$hiddenOrText}}" hideUploader="{{$hideUploader}}" hideUploadDate="{{$hideUploadDate}}" thumbnailClass="{{$thumbnailClass}}" :image="$image"/>
                @endforeach
            </div>
        @endif
        @if(sizeof($videos))
            <div class="{{$gridCols}} sm:p-1">
                @foreach($videos as $video)
                    <x-renderer.attachment2a.type-video openType="{{$openType}}" readOnly="{{$readOnly}}" destroyable="{{$destroyable}}" path="{{$path}}" name="{{$name}}" hiddenOrText="{{$hiddenOrText}}" hideUploader="{{$hideUploader}}" hideUploadDate="{{$hideUploadDate}}" thumbnailClass="{{$thumbnailClass}}" :video="$video" />
                @endforeach
            </div>
        @endif
        @if(sizeof($docs))
            <div class="items-center gap-2 sm:p-1">
                @foreach($docs as $doc)
                    <x-renderer.attachment2a.type-doc openType="{{$openType}}" readOnly="{{$readOnly}}" destroyable="{{$destroyable}}" path="{{$path}}" name="{{$name}}" hiddenOrText="{{$hiddenOrText}}" hideUploader="{{$hideUploader}}" hideUploadDate="{{$hideUploadDate}}" :doc="$doc" />
                @endforeach
            </div>
        @endif
        @if(sizeof($unknowns))
            <div class="items-center gap-2 sm:p-1">
                @foreach($unknowns as $unknown)
                    <x-renderer.attachment2a.type-unknown openType="{{$openType}}" readOnly="{{$readOnly}}" destroyable="{{$destroyable}}" path="{{$path}}" name="{{$name}}" hiddenOrText="{{$hiddenOrText}}" hideUploader="{{$hideUploader}}" hideUploadDate="{{$hideUploadDate}}" :unknown="$unknown" />
                @endforeach
            </div>
        @endif
    @endif
</div>

@if(!$readOnly)
    @if($showUploadButton)
    @php
        $width = $groupMode ? 'w-full' : 'w-1/2' ;
        // $width = 'w-full';
    @endphp
    <div class="flex1 mt-2 custom-file-button no-print1">
        <label for="{{$name}}_{{$groupId}}_browse" 
            class="{{$btnClass}} {{$width}} border cursor-pointer p-2 bg-blue-50 font-normal text-sm whitespace-nowrap" 
            title="{{$btnTooltip}}"
            >{!! $btnLabel !!}
        </label>
        <input name="{{$name}}[toBeUploaded][{{$groupId}}][]" 
            id="{{$name}}_{{$groupId}}_browse" 
            multiple 
            type="file" 
            accept="{{$acceptedExt}}" 
            class="{{$width}} hidden1 h-9 text-sm text-gray-900 p-2.5 rounded-lg bg-white1 cursor-pointer dark:text-gray-300 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
            >
    </div>
    @endif
@endif

@once
<script type="text/javascript">
    var objColName = {};
    function updateToBeDeletedTextBox(id, name) {
        var binIcon = document.getElementById("trashIcon-" + id)
        if (!Object.keys(objColName).includes(name)) {
            objColName[name] = []
            objColName[name].push(id)
            binIcon.classList.remove("hidden")
        } else {
            if (objColName[name].includes(id)) {
                const index = objColName[name].indexOf(id);
                objColName[name].splice(index, 1)
                binIcon.classList.add("hidden")
            } else {
                objColName[name].push(id)
                binIcon.classList.remove("hidden")
            }
        }
        document.getElementById(name).value = objColName[name]
    }
</script>
@endonce
