<x-renderer.card style=" border-gray-300 rounded-lg">
    @foreach($dataComment as $key => $value)
    <div class="flex my-2">
        @php
        $destroyable = $value['id'] !== '';
        @endphp
        <x-renderer.comment destroyable={{$destroyable}} labelName="{{$labelName}}" btnUpload="{{$value['btnUpload'] ?? false}}" name="{{$name}}" type="{{$type}}" id="{{$id}}" :dataComment="$value" action={{$action}} readonly={{true}} showToBeDeleted={{$showToBeDeleted}}></x-renderer.comment>
    </div>
    @endforeach
</x-renderer.card>
