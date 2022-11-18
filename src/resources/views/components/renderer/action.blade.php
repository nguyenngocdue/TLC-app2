<form action="{{route('permissions2.destroy', $id)}}" method="post" title="Delete #{{$id}}">
    @csrf
    @method('DELETE')
    <x-renderer.button>DEL</x-renderer.button>
</form>