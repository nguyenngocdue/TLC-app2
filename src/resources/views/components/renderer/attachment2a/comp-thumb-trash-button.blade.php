@if(!$readOnly)
    @if($destroyable && $attachment['sameEnv'])
        <button type="button" 
                onclick="updateToBeDeletedTextBox({{$attachment['id']}}, '{{$name}}-toBeDeleted')" 
                class="w-10 h-10 m-auto hover:bg-slate-300 rounded-full absolute bottom-[10%] text-[25px]"
                >
            <i class=" text-red-700 fas fa-trash cursor-pointer"></i>
        </button>
    @endif
@endif