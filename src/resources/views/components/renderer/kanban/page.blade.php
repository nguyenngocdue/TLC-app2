@php
    $categoryPage = 'kanban_page_id';
    $classButton = "text-xs border border-gray-300 rounded hover:bg-blue-200 shadow";
    $isOwner = App\Utils\Support\CurrentUser::id() == ($page->owner_id ?? null);
    $icon = $isOwner ? "fa-crown" : "fa-user";
@endphp

{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.17.0/Sortable.min.css">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="{{ asset('js/kanban.js') }}"></script> --}}

<x-renderer.card titleId="divPageCard" title="{{$page->name}}" icon="fa-duotone text-yellow-600 {{$icon}}">
    <div class="container1 mx-auto">
        <div class="overflow-x-auto whitespace-no-wrap">
            <div id="page_{{$pageId}}">
                @foreach($clusters as $cluster)
                    <x-renderer.kanban.cluster :cluster="$cluster" hidden="{{$hidden}}" groupWidth="{{$groupWidth}}"/>
                @endforeach
            </div>
            <script>kanbanInit1("page_", [{{$pageId}}], route_cluster, "{{$categoryPage}}")</script>
            <button class="{{$classButton}} px-4 ml-2 w-80 h-20" type="button" onclick="addANewKanbanObj('page_', {{$pageId}}, route_cluster, '{{$groupWidth}}')">+ Add a Cluster</button>
            <br/>
            <br/>
            <br/>
        </div>
    </div>
</x-renderer.card>

@php
    echo "<script>const currentElapsed = {};";
    foreach($clusters as $cluster){
        foreach($cluster->getGroups as $group){
            echo "currentElapsed[$group->id]={};";
        }
        foreach($cluster->getGroups as $group){
            foreach($group->getTasks as $task){
                $totalElapse = [];
                foreach($task->getTransitions as $transition){
                    $elapsed_seconds = $transition->elapsed_seconds ? $transition->elapsed_seconds : 0;
                    if(!isset($totalElapse[$transition->kanban_group_id])){
                        $totalElapse[$transition->kanban_group_id] = [];
                    }
                    if(!isset( $totalElapse[$transition->kanban_group_id][$transition->kanban_task_id]))
                    {
                        $totalElapse[$transition->kanban_group_id][$transition->kanban_task_id]=0;
                    }
                    $totalElapse[$transition->kanban_group_id][$transition->kanban_task_id] += $elapsed_seconds;
                    
                }
                foreach($totalElapse as $group_id => $subGroups){
                    foreach($subGroups as $task_id => $value){
                        echo "currentElapsed[$group_id][$task_id]=$value;";
                    }
                }
            }
        }
    }
    echo "</script>";
@endphp