<x-renderer.card icon="{{$icon}}" title="{{$title}}">
    <x-renderer.table :columns="$columns" :dataSource="$dataSource" maxH="{{40 * 16}}" showNo=1/>
</x-renderer.card>