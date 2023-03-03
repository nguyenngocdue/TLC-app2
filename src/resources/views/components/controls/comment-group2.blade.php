<x-renderer.card style=" border-gray-300 rounded-lg">
    @foreach($dataSource as $line)
    @dump($line->getOriginal())
    @php $commentAttrs = Str::arrayToAttrs($line->getOriginal());  @endphp
    <x-renderer.comment2 {!! $commentAttrs !!} />
    @endforeach
    {{-- @foreach($dataSource as $key => $value)
        @dump($value)
        @php 
            $commentAttrs = Str::arrayToAttrs($value); 
            $destroyable = $value['id'] !== '';
            dump($commentAttrs);
        @endphp
        <div class="">
            <x-renderer.comment2 {!! $commentAttrs !!} />
        </div>
    @endforeach --}}
</x-renderer.card>
{{-- :attachmentData="$attachmentData" --}}
