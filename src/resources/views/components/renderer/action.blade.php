<form action="{{route('permissions2.destroy', $id)}}" method="post" title="Delete #{{$id}}">
    @csrf
    @method('DELETE')
    <x-renderer.button type="danger" outline=true><i class="fas fa-trash"></i></x-renderer.button>
</form>