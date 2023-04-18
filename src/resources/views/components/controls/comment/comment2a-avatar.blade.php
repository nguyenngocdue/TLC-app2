
<div class="pt-2">
        <x-renderer.image class="rounded-full" w=40 src="{{$comment['owner_id']['avatar']}}" />
        @if($deletable)
            <div class="text-red-600 text-center cursor-pointer">
                <x-renderer.button 
                        htmlType="button" 
                        size="xs" 
                        type="danger" 
                        class="mt-1" 
                        onClick="toggleDeleteComment('{{$comment['group_line_id']}}')"
                        title="Delete this comment #{{$id}}. Please save to commit the deletion. Click again to discard."
                        >
                    <i class="fa fa-trash " ></i>
                </x-renderer.button>
            </div>
            
        @endif
</div>

@once
<script>
function toggleDeleteComment(gr_ln_id){
    //Modify the hidden text input
    const id = "comments[toBeDeleted]["+gr_ln_id+"]"
    const e = getEById(id)
    const oldValue = (e.val() == 'true')
    e.val(!oldValue)

    //Modify the textarea bg color
    const tid = "comments[content]["+gr_ln_id+"]"
    const te = getEById(tid)
    const bgColorToRemove = oldValue ? "bg-red-400 line-through" : "bg-lime-50"
    const bgColorToAdd = oldValue ? "bg-lime-50" : "bg-red-400 line-through"
    te.removeClass(bgColorToRemove).addClass(bgColorToAdd)
}
</script>
@endonce