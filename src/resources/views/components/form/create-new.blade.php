<x-renderer.heading level=3>Create New</x-renderer.heading>
<form action="{{$action}}" method="{{$method0}}" class="grid grid-cols-3">
    @csrf
    @method($method)
    <x-renderer.table tableName='table02' :columns="$columns" :dataSource="$dataSource"></x-renderer.table>
</form>
{{$footer}}