{{-- {{old()}} --}}
<div id="{{$comment01Name}}_div" class="p-4 my-4 bg-gray-250 border rounded-lg shadow-md ">
    <div class="grid grid-cols-12 gap-4 ">
        <div class="col-span-3">
            <input name='{{$comment01Name}}[id][{{$rowIndex}}]' type="{{$commentDebugType}}" value="{{$commentId}}" readonly class="readonly w-full border">
        </div>
        <div class="col-span-3">
            <input name='{{$comment01Name}}[category][{{$rowIndex}}]' type="{{$commentDebugType}}" value="{{$category}}" readonly class="readonly w-full border">
        </div>
        <div class="col-span-3">
            <input name='{{$comment01Name}}[commentable_type][{{$rowIndex}}]' type="{{$commentDebugType}}" value="{{$commentableType}}" readonly class="readonly w-full border">
        </div>
        <div class="col-span-3">
            <input name='{{$comment01Name}}[commentable_id][{{$rowIndex}}]' type="{{$commentDebugType}}" value="{{$commentableId}}" readonly class="readonly w-full border">
        </div>
    </div>
    <div class="grid grid-cols-12 gap-2 flex-nowrap">
        <div class=" grid col-span-11 text-center flex-nowrap">
            <div class="grid grid-cols-12 gap-4 ">
                <div class="col-span-4">
                    {{-- {{$allowedChangeOwner ? "true" : "false"}} --}}
                    <div class="relative">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                            <img src="{{$ownerAvatar}}" class="rounded-full h-6 w-6 object-cover">
                        </div>
                        <input value="{{$ownerName}}" readonly type='text' class='readonly {{$classList}} pl-10'>
                        <input name='{{$comment01Name}}[owner_id][{{$rowIndex}}]' value="{{$ownerId}}" readonly type='hidden' class='readonly {{$classList}}'>
                    </div>
                </div>
                <div class="col-span-4 ">
                    <input name='{{$comment01Name}}[position_rendered][{{$rowIndex}}]' value="{{$positionRendered}}" readonly type='text' class='readonly {{$classList}}'>
                </div>

                <div class="col-span-4">
                    <div class="relative">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                            <i class="fa-duotone fa-calendar"></i>
                        </div>
                        <input type="text" value="{{$datetime}}" readonly class="readonly {{$classList}} pl-8">
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-span-1 text-center flex">
            <div class="m-auto text-center flex-1">
                <div class="flex">
                    @if($allowedDelete == true) 
                        @php $destroyName="{$comment01Name}[DESTROY_THIS_LINE][$rowIndex]"; @endphp
                        <button type="button" onclick='trashComment("{{$destroyName}}","{{$comment01Name}}_div")' class="w-10 h-10 m-auto hover:bg-slate-300 rounded-full">
                            <i class="text-[#d11a2a] fas fa-trash cursor-pointer"></i>
                        </button>
                        <input name="{{$destroyName}}" id="{{$destroyName}}" type="{{$commentDebugType}}" value="" class="w-10" />
                    @else
                        <button type="button" class="w-10 h-10 m-auto hover1:bg-slate-300 rounded-full">
                            <i class="text-gray-400 fas fa-trash cursor-not-allowed"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-span-12 mt-2 rounded-lg border1 border-gray-300 overflow-hidden">
            @php $contentName = "{$comment01Name}[content][$rowIndex]" @endphp
            <textarea name="{{$contentName}}" rows="2" @readonly($readonly) placeholder="Type here..." class="{{$readonly?"readonly":""}} {{$classList}}"
            >{{old($contentName, $content) ?? $content }}</textarea>
        </div>
        @if($allowedAttachment)
            <div class="col-span-12 mt-2 rounded-lg border border-gray-300 overflow-hidden">
                <x-renderer.attachment2 name="{{$comment01Name}}[comment_attachment][toBeUploaded]" />
                {{-- readonly="{{$readonly}}" destroyable={{$allowedDelete}} categoryName={{$comment01Name}} :attachmentData="$a[{{$rowIndex}}]ttachmentData" action={{$action}} labelName={{$labelName}} path={{$path}} /> --}}
            </div>
        @endif
    </div>
</div>

@once
<script>
    const trashComment = (id, divId) =>{
        const lastValue = getEById(id).val() === 'true'
        getEById(id).val(!lastValue)
        if(lastValue) document.getElementById(divId).classList.remove("bg-red-300") 
        else document.getElementById(divId).classList.add("bg-red-300")
    }
    </script>
@endonce