
<div class="pt-4">
        <x-renderer.image class="rounded-full" w=40 src="{{$comment['owner_id']['avatar']}}" />
        @if($deletable)
            <div class="text-red-600 cursor-pointer" title="Delete this comment #{{$id}}">
                <i class="fa fa-trash " ></i>
            </div>
        @endif
</div>