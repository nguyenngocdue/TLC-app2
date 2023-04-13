<div title="#{{$comment['id']['value']}}" class="mb-2">
    {{-- <input name="{{$comment['content']['name']}}" value="{{$comment['content']['value']}}" > --}}
    <input name="{{$comment['id']['name']}}" value="{{$comment['id']['value']}}" type="{{$input_or_hidden}}" >

    @if(is_null($comment['id']['value']))
        <input name="{{$comment['position_rendered']['name']}}" value="{{$comment['position_rendered']['value']}}" type="{{$input_or_hidden}}">
        <input name="{{$comment['owner_id']['name']}}" value="{{$comment['owner_id']['value']}}" type="{{$input_or_hidden}}">
        <input name="{{$comment['created_at']['name']}}" value="{{$comment['created_at']['value']}}" type="{{$input_or_hidden}}">

        <input name="{{$comment['commentable_type']['name']}}" value="{{$comment['commentable_type']['value']}}" type="{{$input_or_hidden}}">
        <input name="{{$comment['commentable_id']['name']}}" value="{{$comment['commentable_id']['value']}}" type="{{$input_or_hidden}}">
        <input name="{{$comment['category']['name']}}" value="{{$comment['category']['value']}}" type="{{$input_or_hidden}}">
    @endif

    <div class="grid grid-cols-12">
        @if($comment['mine'])
            {{-- <div class="col-span-1"> --}}
            <div class="col-span-12 md:col-span-10 md:col-start-2">
                <x-renderer.card title="{{$title}}" px=0 py=0 class="bg-gray-100">
                    <textarea rows="2" class="w-full border-gray-100 bg-gray-100 px-2" name="{{$comment['content']['name']}}"
                    >{{$comment['content']['value']}}</textarea>
                </x-renderer.card>
            </div> 
            <div class="col-span-12 md:col-span-1 text-xs flex justify-start">
                <div class="pt-4">
                    <x-renderer.image class="rounded-full" w=40 src="{{$comment['owner_id']['avatar']}}" />
                </div>
            </div>
        @else
            <div class="col-span-12 md:col-span-1 text-xs flex justify-end">
                <div class="pt-4">
                    <x-renderer.image class="rounded-full" w=40 src="{{$comment['owner_id']['avatar']}}" />
                </div>
            </div>
            <div class="col-span-12 md:col-span-10">
                <x-renderer.card title="{{$title}}" px=0 py=0 class="bg-gray-100">
                    <textarea rows="2" class="w-full border-gray-100 bg-gray-100 px-2" name="{{$comment['content']['name']}}"
                        >{{$comment['content']['value']}}</textarea>
                </x-renderer.card>
            </div> 
        @endif
    </div>
</div>


