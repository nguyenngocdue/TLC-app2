<div class="flex flex-col container1 mx-aut1o w-full">
    @if(sizeof($attachments) ==0 && sizeof($docs) == 0)
    <x-renderer.emptiness p="2" class="border" message="There is no attachment to be found." />
    @else
    <div class="grid {{$gridCols}} lg:gap-3 md:gap-2 sm:gap-1 p-1 break-inside-avoid">
        @foreach($attachments as $attachment)
        @php
        [$hasOrphan,$sameEnv,$extension,$border,$title] = App\Utils\Support\HandleFieldsAttachment::handle($attachment)
        @endphp
        @if($hasOrphan)
            <input name="{{$name}}[toBeAttached][]" value="{{$attachment['id']}}" type="{{$hiddenOrText}}" />
        @endif
        <div class="border-{{$border}}-300 h-full">
            <div 
            name='{{$name}}' 
            title="{{$title}}" 
            class="relative flex mx-1 flex-col items-center p-025vw border-2 rounded-lg  group/item overflow-hidden bg-inherit"
            >
                {{-- This is the image --}}
                @if(in_array($extension,["png","gif","jpg","jpeg"]))
                    @if($openType == '_blank') <a target="_blank" href="{{$path.$attachment['url_media']}}"> @endif
                    <img class="rounded" src="{{$path.$attachment['url_thumbnail']}}" alt="{{$attachment['filename']}}" />
                    @if($openType == '_blank') </a> @endif
                @elseif(in_array(strtolower($extension), ["csv","pdf","zip"]))
                    <i class="w-auto h-full object-cover fa-light fa-file-{{$extension=='zip' ? 'arrow-down' : $extension}} text-9xl"></i>
                @elseif(in_array(strtolower($extension), ["mov","mp4","webm"]))
                @if($openType == '_blank') <a class="cursor-pointer" target="_blank" href="{{$path.$attachment['url_media']}}"> @endif
                    <video class="w-auto rounded h-full object-cover" src="{{$path.$attachment['url_media']}}" alt="{{$attachment['filename']}}"></video>
                    @if($openType == '_blank') </a> @endif
                    <div class="z-10" style="margin-top: -40%;" >
                        <a class="cursor-pointer" target="_blank" href="{{$path.$attachment['url_media']}}">
                            <i class="text-3xl-vw text-3xl text-yellow-400 fa-solid fa-circle-play"></i>
                        </a>
                    </div>
                @elseif($extension === 'svg')
                    <img class="w-auto h-full object-cover" src="{{$path.$attachment['url_media']}}" alt="{{$attachment['filename']}}" />
                @else
                @endif
                {{-- This is to show the toBeDeleted trash icon --}}
                <span id="trashIcon-{{$attachment['id']}}" class="hidden">
                    <i class="text-7xl text-pink-500 fa-sharp fa-solid fa-circle-xmark cursor-pointer absolute top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%]"></i>
                </span>
                @if($openType == 'gallery')
                    @php
                        if(!in_array(strtolower($extension),\App\Utils\Constant::EXTENSIONS_OF_FILE_GALLERY)) {
                            $onClick = '';
                            $url = $path.$attachment['url_media'];
                            $href = "$url";
                        } else {
                            $onClick = "openGallery(".$attachment['id'].")";
                        }
                    @endphp

                    <div class="invisible flex justify-center hover:bg-[#00000080] group-hover/item:visible before:absolute before:-inset-1  before:bg-[#00000080]">
                        <a title="{{$attachment['filename']}}" 
                            onclick="{!!$onClick!!}" 
                            {!! $onClick ? "" : "href='$href'" !!}  
                            target='_blank' 
                            class="cursor-pointer hover:underline  text-white hover:text-blue-300 px-2 absolute top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] text-lg text-center w-full"
                            >
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
                @endif
            </div>
                @php
                $uid = $attachment['owner_id'] ?? 1;
                [$src,$firstName,$displayName] = App\Utils\Support\GetInfoUserById::get($uid);
                @endphp
            @if(!$hideUploader)
            <span class="flex items-center gap-1 mt-1 justify-center" title="Uploaded by {{$displayName}} (#{{$uid}})">
                <img style="width:17%" class="w1-6 rounded-full" src="{{$src}}" />
                {{$firstName}} 
            </span>
            @endif
            @if(!$hideUploadDate)
            <span class="flex justify-center">{{date('d/m/Y',strtotime($attachment['created_at'] ?? ''))}}</span>
            @endif
        </div>
        @endforeach
    </div>
    @foreach($docs as $doc)
    <div class="border rounded text-left px-2 my-0.5 hover:bg-blue-200">
            @php
            [$hasOrphan,$sameEnv] = App\Utils\Support\HandleFieldsAttachment::handle($doc);
            $uid = $doc['owner_id'] ?? 1;
            [$src,$firstName,$displayName] = App\Utils\Support\GetInfoUserById::get($uid);
            @endphp
            @if($hasOrphan)
            <input name="{{$name}}[toBeAttached][]" value="{{$doc['id']}}" type="{{$hiddenOrText}}" />
            @endif
            <div class="items-center gap-2 p-1">
                <div class="flex gap-2 group relative items-center">
                    <a href="{{$path.$doc['url_media']}}" 111111 target="_blank" class="text-blue-500 w-full text-md-vw text-left">
                        <p><i class="fa-light fa-file mr-1"></i>{{$doc['filename']}}</p>
                        
                    </a>
                    <span id="trashIcon-{{$doc['id']}}" class="hidden">
                        <i class="text-xl text-pink-500 fa-sharp fa-solid fa-circle-xmark cursor-pointer absolute right-7 top-[50%] translate-x-[-50%] translate-y-[-50%]"></i>
                    </span>
                    @if(!$readOnly)
                        @if($destroyable && $sameEnv)
                            <button type="button" 
                            onclick="updateToBeDeletedTextBox({{$doc['id']}}, '{{$name}}-toBeDeleted')" 
                            class="w-10 h-10 m-auto hover:bg-slate-300 rounded-full invisible absolute top-0 right-0 group-hover:visible bottom-[10%]">
                                <i class=" text-red-700 fas fa-trash cursor-pointer"></i>
                            </button>
                        @endif
                    @endif
                    
                </div>
                <div class="flex items-center gap-1" title="Uploaded by {{$displayName}} (#{{$uid}})">
                    <img style="width:4%" class="rounded-full" src="{{$src}}" />
                    <span>{{$displayName}}</span>
                    <p class="text-sm-vw">{{date('d/m/Y',strtotime($doc['created_at'] ?? ''))}}</p>
                </div>
                
            </div>
        </div>
    @endforeach
    {{-- <div class="p-1"></div> --}}
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
