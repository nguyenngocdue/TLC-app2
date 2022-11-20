<x-renderer.heading>Create New</x-renderer.heading>
<form action="{{$action}}" method="{{$method0}}" class="grid grid-cols-3">
    @csrf
    @method($method)
    <x-renderer.table :columns="$columns" :dataSource="$dataSource"></x-renderer.table>
</form>
{{$footer}}