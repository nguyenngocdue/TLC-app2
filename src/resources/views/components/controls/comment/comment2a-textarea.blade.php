<x-renderer.card title="{{$title}}" px=0 py=0 class="bg-gray-100 px-2">
    <textarea @readonly($readOnly) rows="2" name="{{$comment['content']['name']}}"
    class="{{$class}} w-full border-gray-100 focus:border-gray-100 rounded"
    >{{$comment['content']['value']}}</textarea>
</x-renderer.card>