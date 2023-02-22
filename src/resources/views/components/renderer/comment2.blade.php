{{-- {{old()}} --}}
<div id="{{$comment01Name}}_div" class="p-4 my-4 bg-gray-250 border rounded-lg shadow-md ">
    <div class="grid grid-cols-12 gap-4 ">
        <div class="col-span-3">
            <input name='{{$comment01Name}}[id][{{$rowIndex}}]' type="{{$commentDebugType}}" value="{{$commentId}}" readonly class="w-full border">
        </div>
        <div class="col-span-3">
            <input name='{{$comment01Name}}[category][{{$rowIndex}}]' type="{{$commentDebugType}}" value="{{$fieldId}}" readonly class="w-full border">
        </div>
        <div class="col-span-3">
            <input name='{{$comment01Name}}[commentable_type][{{$rowIndex}}]' type="{{$commentDebugType}}" value="{{$commentableType}}" readonly class="w-full border">
        </div>
        <div class="col-span-3">
            <input name='{{$comment01Name}}[commentable_id][{{$rowIndex}}]' type="{{$commentDebugType}}" value="{{$commentableId}}" readonly class="w-full border">
        </div>
    </div>
    <div class="grid grid-cols-12 gap-2 flex-nowrap">
        <div class=" grid col-span-11 text-center flex-nowrap">
            <div class="grid grid-cols-12 gap-4 ">
                <div class="col-span-4">
                    {{-- {{$ownerObj ? $ownerObj->name : "NULL"}} --}}
                    {{-- {{$allowedChangeOwner ? "true" : "false"}} --}}
                    <input name='{{$comment01Name}}[owner_id][{{$rowIndex}}]' value="{{$ownerId}}" readonly type='text' class='readonly dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full text-sm focus:border-purple-400 focus:outline-none'>
                </div>
                <div class="col-span-4 ">
                    <input name='{{$comment01Name}}[position_rendered][{{$rowIndex}}]' value="{{$positionRendered}}" readonly type='text' class='readonly dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full text-sm focus:border-purple-400 focus:outline-none'>
                </div>

                <div class="col-span-4">
                    <div class="relative">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                            <i class="fa-duotone fa-calendar"></i>
                        </div>
                        <input datepicker type="text" value="{{$datetime}}" readonly placeholder="" class="readonly dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 border border-gray-300 text-gray-900 text-sm rounded-lg focus:border-purple-400 focus:outline-none block w-full pl-8 p-2.5">
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-span-1 text-center flex">
            {{-- <div class="col-span-1 flex-1">
                <input value="#{{$id}}" readonly type='text' class='readonly dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 text-center border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full text-sm focus:border-purple-400 focus:outline-none'>
            </div> --}}
            @if($allowedDelete == true) 
            <div class=" m-auto text-center flex-1">
                <div class="flex ">
                    @php $destroyName="{$comment01Name}[DESTROY_THIS_LINE][$rowIndex]"; @endphp
                    <button type="button" onclick='trashComment("{{$destroyName}}","{{$comment01Name}}_div")' class=" w-10 h-10 m-auto hover:bg-slate-300 rounded-full">
                        <i class="text-[#d11a2a] fas fa-trash cursor-pointer"></i>
                    </button>
                    <input name="{{$destroyName}}" id="{{$destroyName}}" type="{{$commentDebugType}}" value="" class="w-10" />
                </div>
            </div>
            @endif
        </div>
        <div class="col-span-12 mt-2 rounded-lg border border-gray-300 overflow-hidden">
            @php $contentName = "{$comment01Name}[content][$rowIndex]" @endphp
            <textarea name="{{$contentName}}" rows="2" @readonly($readonly) placeholder="Type here..." class="{{$readonly?"readonly":""}} bg-inherit border-none resize-none text-gray-900 p-2.5 dark:placeholder-gray-400 block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray">{{old($contentName, $content) ?? $content }}</textarea>
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