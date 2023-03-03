<x-renderer.card style="border-gray-300 rounded-lg" px="2" py="1">
    @php $rowIndex = 0; @endphp
    @foreach($dataSource as $line)
        {{-- @dump($line->getAttributes()) --}}
        {{-- @dump($rowIndex) --}}
        @php $attrs = $line->getAttributes(); @endphp
        {{-- @dump($attrs) --}}
        <x-renderer.comment2 
                comment01Name="{{$comment01Name}}"
                rowIndex="{{$rowIndex++}}"

                content="{{$attrs['content']}}"
                owner_id="{{$attrs['owner_id']}}"
                position_rendered="{{$attrs['position_rendered']}}"
                commentable_type="{{$attrs['commentable_type']}}"
                commentable_id="{{$attrs['commentable_id']}}"
                category="{{$attrs['category']}}"
                datetime="{{$attrs['created_at']}}"
                commentId="{{$attrs['commentId']}}"

                readonly="{{!true?1:0}}"
            ></x-renderer.comment2>
    @endforeach
    @if($allowAppending)
        <x-renderer.comment2 
                comment01Name="{{$comment01Name}}"
                rowIndex="{{$rowIndex}}"

                content=""
                owner_id="{{$userId}}"
                position_rendered="{{$userPosition}}"
                commentable_type="{{$commentable_type}}"
                commentable_id="{{$commentable_id}}"
                category="{{$fieldId}}"
                datetime="{{$now}}"
            ></x-renderer.comment2>
    @endif
</x-renderer.card>

<input class="bg-gray-200" readonly="" name="tableNames[{{$comment01Name}}]" value="comments" type="hidden">
