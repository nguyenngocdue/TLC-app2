<x-renderer.card title="Project Members of {{$project->name}}" class="bg-white border" icon="fa-sharp fa-regular fa-users" >
    <x-renderer.table showNo=1 :columns="$columns" :dataSource="$dataSource" />
</x-renderer.card>