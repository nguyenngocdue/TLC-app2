<div class="flex flex-col container mx-aut1o w-full">
    @if(sizeof($attachments) ==0)
    <x-renderer.emptiness p="2" class="border" message="There is no attachment to be found." />
    @else
    <div class="grid grid-cols-5 lg:gap-3 md:gap-2 sm:gap-1 mb-1 p-1 hidden1">
        @foreach($attachments as $attachment)
        @php
        $hasOrphan = isset($attachment['hasOrphan']) && $attachment['hasOrphan'] ;
        $border = $hasOrphan ? "red" : "gray";
        $title = $hasOrphan ? "Orphan image found. Will attach after this document is saved.":"";
        $extension = $attachment['extension'] ?? "";
       
        $folder = $attachment['url_folder'] ?? '';
        $isProd = str_starts_with($folder, 'app2_prod') || str_starts_with($folder, 'avatars');
        $isTesting = str_starts_with($folder, 'app2_beta');
        $isDev = !($isProd || $isTesting);

        $sameEnv = false;
        if(app()->isProduction() && $isProd) $sameEnv = true; 
        if(app()->isTesting() && $isTesting) $sameEnv = true; 
        if(app()->isLocal() && $isDev) $sameEnv = true;        

        @endphp
        @if($hasOrphan)
        <input name="{{$name}}[toBeAttached][]" value="{{$attachment['id']}}" type="{{$hiddenOrText}}" />
        @endif
        <div name='{{$name}}' title="{{$title}}" class="border-{{$border}}-300 relative h-full flex mx-1 flex-col items-center p-1 border-2 rounded-lg  group/item overflow-hidden bg-inherit">
            {{-- This is the image --}}
            @if(in_array($extension,["png","gif","jpg","jpeg","webp"]))
            <img src="{{$path.$attachment['url_thumbnail']}}" alt="{{$attachment['filename']}}" />
            @elseif(in_array($extension,["csv","pdf","zip"]))
            <i class="w-auto h-full object-cover fa-light fa-file-{{$extension=='zip' ? 'arrow-down' : $extension}} text-9xl"></i>
            @elseif(in_array($extension,["mov","mp4","MP4","webm"]))
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
                @php
                    $onClick = "openGallery(".$attachment['id'].")";
                    if(!in_array($extension,\App\Utils\Constant::EXTENSIONS_OF_FILE_GALLERY)) {
                        $onClick = '';
                        $url = $path.$attachment['url_media'];
                        $href = "href='$url'";
                    };
                @endphp
                <a title="{{$attachment['filename']}}" onclick={!!$onClick!!} 
                {!! $onClick ? "" : $href !!}  
                target='_blank' class="hover:underline text-white hover:text-blue-500 px-2 absolute top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] text-lg text-center w-full">
                    <span class="text-sm">{{$attachment['filename']}}</span>
                </a>
                @if(!$readOnly)
                    @if($destroyable && $sameEnv)
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
    <input id="{{$name}}-toBeDeleted" name="{{$name}}[toBeDeleted]" readonly type='{{$hiddenOrText}}' class='p-2.5 w-full bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none  focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray '>
@endif
@if(!$readOnly)
    @if($showUploadFile)
    <div class="flex mt-2 custom-file-button no-print1">
        <label for="{{$name}}_browse" class="{{$btnClass}} w-72 border cursor-pointer p-2 bg-blue-50 font-normal text-sm" title="{{$messageTitle}}">{!! $message !!}</label>
        <input name="{{$name}}[toBeUploaded][]" id="{{$name}}_browse" multiple type="file" accept="{{$acceptAttachment}}" class="hidden1 w-1/2 h-9 text-sm text-gray-900 p-2.5 rounded-lg bg-white1 border border-white cursor-pointer dark:text-gray-300 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
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
