<x-renderer.card style=" border-gray-800 border-4  rounded-lg">
    @foreach($dataComment as $key => $value)
    <x-renderer.comment labelName={{$label}} btnAttach="{{$value['btnAttach'] ?? false}}" name="{{$name}}" type="{{$type}}" id="{{$id}}" :dataComment="$value" action={{$action}} readonly="{{true}}"></x-renderer.comment>
    @endforeach
</x-renderer.card>
