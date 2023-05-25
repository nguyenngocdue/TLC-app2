<x-renderer.card title="{{$title}}" class="bg-gray-100 px-2 py-0">
    <textarea 
        @readonly($readOnly) 
        rows="2" 
        id="{{$comment['content']['name']}}"
        name="{{$comment['content']['name']}}"
        class="{{$class}} w-full border-gray-100 focus:border-gray-100 rounded"
    >{{$comment['content']['value']}}</textarea>
</x-renderer.card>