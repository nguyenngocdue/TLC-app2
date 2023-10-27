@php
    $categoryPage = 'kanban_page_id';
    $classButton = "text-xs border border-gray-300 rounded hover:bg-blue-200 shadow";
    $isOwner = App\Utils\Support\CurrentUser::id() == ($page->owner_id ?? null);
    $icon = $isOwner ? "fa-crown" : "fa-user";
@endphp

{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.17.0/Sortable.min.css">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="{{ asset('js/kanban.js') }}"></script> --}}

<x-renderer.card idHtml="cardPage000" titleId="divPageCard" title="{{$page->name}}" icon="fa-duotone text-yellow-600 {{$icon}}">
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
    echo "<script>";
    foreach($clusters as $cluster){
        $totalElapse = [];
        foreach($cluster->getGroups as $group){
            foreach($group->getTasks as $task){
                $totalElapse[$task->id] = [];
                foreach($task->getTransitions as $transition){
                    if($transition->elapsed_seconds){
                        $elapsed_seconds = $transition->elapsed_seconds;
                    } else {
                        // $elapsed_seconds = \Carbon\Carbon::now()->diffInSeconds($transition->start_at);
                        $workingShiftService = new \App\Http\Services\WorkingShiftService();
                        $result = $workingShiftService->calculateShiftDurationByUser($transition->start_at, now(), $task->assignee_1);
                        // Log::info("A");
                        ['shift_seconds'=>$elapsed_seconds ]= $result;
                    }

                    if(!isset($totalElapse[$task->id][$transition->kanban_group_id]))$totalElapse[$task->id][$transition->kanban_group_id] = [];
                    if(!isset( $totalElapse[$task->id][$transition->kanban_group_id][$transition->kanban_task_id]))$totalElapse[$task->id][$transition->kanban_group_id][$transition->kanban_task_id]=0;
                    $totalElapse[$task->id][$transition->kanban_group_id][$transition->kanban_task_id] += $elapsed_seconds;
                    
                }
            }
        }
        // dump($totalElapse);
        $groups = [];
        foreach($totalElapse as $elapses){
            $groups = [...$groups, ...array_keys($elapses)];
        }
        $groups = array_unique($groups);
        foreach($groups as $group_id){
            echo "currentElapsed[$group_id]={};";
        }
        foreach($totalElapse as $elapses){
            foreach($elapses as $group_id => $groups){
                foreach($groups as $task_id => $value){
                    echo "currentElapsed[$group_id][$task_id]=$value;";
                }
            }
        }
    }
    echo "</script>";
@endphp