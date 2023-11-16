<div title="{{$legendTooltip}}" class="mb-2">
    {{-- <input name="{{$comment['content']['name']}}" value="{{$comment['content']['value']}}" > --}}
    <input name="{{$comment['id']['name']}}" value="{{$comment['id']['value']}}" title="id" class="border" type="{{$input_or_hidden}}" >
    <input name="{{$comment['category_name']['name']}}" value="{{$comment['category_name']['value']}}" title="category_name" class="border" type="{{$input_or_hidden}}">

    @if(is_null($comment['id']['value']))
        <input name="{{$comment['position_rendered']['name']}}" value="{{$comment['position_rendered']['value']}}" title="position_rendered" class="border" type="{{$input_or_hidden}}">
        <input name="{{$comment['owner_id']['name']}}" value="{{$comment['owner_id']['value']}}" title="owner_id" class="border" type="{{$input_or_hidden}}">
        <input name="{{$comment['created_at']['name']}}" value="{{$comment['created_at']['value']}}" title="created_at" class="border" type="{{$input_or_hidden}}">

        <input name="{{$comment['commentable_type']['name']}}" value="{{$comment['commentable_type']['value']}}" title="commentable_type" class="border" type="{{$input_or_hidden}}">
        <input name="{{$comment['commentable_id']['name']}}" value="{{$comment['commentable_id']['value']}}" title="commentable_id" class="border" type="{{$input_or_hidden}}">
        <input name="{{$comment['category']['name']}}" value="{{$comment['category']['value']}}" title="category" class="border" type="{{$input_or_hidden}}">
    @endif
    
    <div class="grid grid-cols-12">
        @if($comment['mine'])
            @if($deletable)
                <input id="{{$comment['toBeDeleted']['name']}}" name="{{$comment['toBeDeleted']['name']}}" value="false" title="toBeDeleted" class="border" type="{{$input_or_hidden}}">
            @endif
            <div class="col-span-10 col-start-2">
                <x-controls.comment.comment2a-textarea :title="$title" :readOnly="$readOnly" :comment="$comment" class="bg-lime-50"/>
            </div> 
            <div class="col-span-1 text-xs flex justify-start">
                <x-controls.comment.comment2a-avatar :comment="$comment" :deletable="$deletable" id="{{$comment['id']['value']}}" class="justify-start"/>
            </div>
        @else
            <div class="col-span-1 text-xs flex justify-end">
                <x-controls.comment.comment2a-avatar :comment="$comment" :deletable="$deletable" id="{{$comment['id']['value']}}" class="justify-end"/>
            </div>
            <div class="col-span-10">
                <x-controls.comment.comment2a-textarea :title="$title" :readOnly="$readOnly" :comment="$comment" class="bg-gray-100"/>
            </div> 
        @endif
    </div>
</div>


