<x-renderer.card style="border-gray-300 rounded-lg px-2 py-1">
    @if(!count($comments) && $readOnly)
        <div>(None)</div>
    @endif
    @foreach($params as $comment)
        <x-controls.comment.comment2a 
            :comment="$comment" 
            :readOnly="$readOnly"
            :debug="$debug" 
            />
    @endforeach
</x-renderer.card>