<div class="flex flex-col container mx-aut1o w-full">
    @if(sizeof($attachments) ==0)
    <x-renderer.emptiness p="2" class="border" message="There is no item to be found." />
    @else
    <div class="grid grid-cols-5 lg:gap-3 md:gap-2 sm:gap-1 mb-1 p-1 hidden1">
        @foreach($attachments as $attachment)
        @php
        $hasOrphan = isset($attachment['hasOrphan']) && $attachment['hasOrphan'] ;
        $border = $hasOrphan ? "red" : "gray";
        $title = $hasOrphan ? "Orphan image found. Will attach after this document is saved.":"";
        $extension = $attachment['extension'] ?? "";
        @endphp
        @if($hasOrphan)
        <input name="{{$name}}[toBeAttached][]" value="{{$attachment['id']}}" type="hiddenOrText" />
        @endif
        <div name='{{$name}}' title="{{$title}}" class="border-{{$border}}-300 relative h-full flex mx-1 flex-col items-center p-1 border rounded-lg  group/item overflow-hidden bg-inherit">
            {{-- This is the image --}}
            @if(in_array($extension,["png","gif","jpg","jpeg","webp"]))
            <img src="{{$path.$attachment['url_thumbnail']}}" alt="{{$attachment['filename']}}" />
            @elseif(in_array($extension,["csv","pdf","zip"]))
            <i class="w-auto h-full object-cover fa-light fa-file-{{$extension=='zip' ? 'arrow-down' : $extension}} text-9xl"></i>
            @elseif($extension == 'mp4')
            <video class="w-auto h-full object-cover" src="{{$path.$attachment['url_media']}}" alt="{{$attachment['filename']}}"></video>
            @elseif($extension === 'svg')
            <img class="w-auto h-full object-cover" src="{{$path.$attachment['url_media']}}" alt="{{$attachment['filename']}}" />
            @else
            @endif
            {{-- This is to show the toBeDeleted trash icon --}}
            <span id="trashIcon-{{$attachment['id']}}" class="hidden">
                <i class="text-7xl text-pink-500 fa-sharp fa-solid fa-circle-xmark cursor-pointer absolute top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%]"></i>
            </span>
            {{-- This is to show the thin layer which has the filename and trash button --}}
            <div class="invisible flex justify-center hover:bg-[#00000080] group-hover/item:visible before:absolute before:-inset-1  before:bg-[#00000080]">
                <a title="{{$attachment['filename']}}" href="{{$path.$attachment['url_media']}}" target='_blank' class="hover:underline text-white hover:text-blue-500 px-2 absolute top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] text-lg text-center w-full">
                    <span class="text-sm">{{$attachment['filename']}}</span>
                </a>
                @if(!$readOnly)
                    @if($destroyable)
                    <button type="button" onclick="updateToBeDeletedTextBox({{$attachment['id']}}, '{{$name}}-toBeDeleted')" class="w-10 h-10 m-auto hover:bg-slate-300 rounded-full absolute bottom-[10%] text-[25px]">
                        <i class=" text-red-700 fas fa-trash cursor-pointer"></i>
                    </button>
                    @endif
                @endif
            </div>
            <span>{{date('d/m/Y',strtotime($attachment['created_at'] ?? ''))}}</span>
        </div>
        @endforeach
    </div>
    @endif
</div>
@if(!$readOnly)
    @if($showToBeDeleted)
    <input id="{{$name}}-toBeDeleted" name="{{$name}}[toBeDeleted]" readonly type='hiddenOrText' class='p-2.5 w-full bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none  focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray '>
    @endif
@endif
@if(!$readOnly)
    @if($showUploadFile)
    <input name="{{$name}}[toBeUploaded][]" id="{{$name}}" multiple type="file" accept="{{$acceptAttachment}}" class="hidden block w-full text-sm text-gray-900 p-2.5 rounded-lg bg-white border border-white cursor-pointer dark:text-gray-300 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
    <label for="{{$name}}" class="{{$btnClass}} border cursor-pointer p-2 bg-gray-400 mt-2 font-normal" title="{{$messageTitle}}">{!! $message !!}</label>
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
