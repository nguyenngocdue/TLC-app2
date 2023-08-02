<x-renderer.card icon="{{$icon}}" title="{{$title}}">
    <x-renderer.table :columns="$columns" :dataSource="$dataSource" maxH="40"/>
</x-renderer.card>