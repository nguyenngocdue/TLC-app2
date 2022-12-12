<x-renderer.card style=" border-gray-300 rounded-lg mt-2">
    @foreach($dataComment as $key => $value)
    {{-- @dump($value) --}}
    <x-renderer.comment labelName="{{$labelName}}" btnAtt="{{$value['btnAtt'] ?? false}}" name="{{$name}}" type="{{$type}}" id="{{$id}}" :dataComment="$value" action={{$action}} readonly="{{true}}"></x-renderer.comment>
    @endforeach
</x-renderer.card>
