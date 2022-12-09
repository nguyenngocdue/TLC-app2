@foreach($dataComment as $key => $value)
<x-renderer.comment name="{{$name}}" type="{{$type}}" id="{{$id}}" :dataComment="$value" action={{$action}} readonly="{{true}}"></x-renderer.comment>
@endforeach
