<x-renderer.card style="border-gray-300 rounded-lg">
    @foreach($dataSource as $rowIndex => $line)
    {{-- @dump($line->getAttributes()) --}}
    {{-- @dump($rowIndex) --}}
    @php $attrs = $line->getAttributes(); @endphp
    {{-- @dump($attrs) --}}
    <x-renderer.comment2 
            content="{{$attrs['content']}}"
            owner_id="{{$attrs['owner_id']}}"
            position_rendered="{{$attrs['position_rendered']}}"
            commentable_type="{{$attrs['commentable_type']}}"
            commentable_id="{{$attrs['commentable_id']}}"
            category="{{$attrs['category']}}"
            datetime="{{$attrs['created_at']}}"
            commentId="{{$attrs['commentId']}}"
        ></x-renderer.comment2>
    @endforeach
</x-renderer.card>
