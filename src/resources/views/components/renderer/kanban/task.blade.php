@php 
 $classTask = "bg-white p-1 shadow rounded text-xs w-full focus:border-1 bold my1-1";
 $modalId = "modal-task";
 $title = "$task->description\n(#{$task->id})";
//  dump($group->time_counting_type);
//  $class = $group->time_counting_type === "0" ? "hidden" : "";
@endphp

<div id="task_parent_{{$task->id}}" data-id="task_{{$task->id}}" class="bg-white p-2 shadow rounded text-xs my1-1" >
    <div class=" flex justify-between mb-2">
        <div class="w-full">
            <h2 id="lbl_task_{{$task->id}}" class="cursor-grab" >
                <span id="caption_task_{{$task->id}}" title="{{$title}}" onclick="onClickToEdit({{$task->id}},'lbl_task', 'txt_task')" class="cursor-pointer">{{$task->name ?? "???"}} </span>
            </h2>

            <input id="taskParentId_{{$task->id}}" class="bg-gray-300 rounded px-2" type="{{$hidden??"hidden"}}" value="{{$task->kanban_group_id}}"/>
            <input id="taskParentTimeCountingType_{{$task->id}}" class="bg-gray-300 rounded px-2" type="{{$hidden??"hidden"}}" value="{{$group->time_counting_type}}"/>
            <input id="taskParentPreviousGroupId_{{$task->id}}" class="bg-gray-300 rounded px-2" type="{{$hidden??"hidden"}}" value="{{$group->previous_group_id}}"/>
            <input id="taskParentRectifiedGroupId_{{$task->id}}" class="bg-gray-300 rounded px-2" type="{{$hidden??"hidden"}}" value="{{$group->rectified_group_id}}"/>
            <input id="intervalId_{{$task->id}}" class="bg-gray-300 rounded px-2" type="{{$hidden??"hidden"}}"/>
            
            <textarea id="txt_task_{{$task->id}}" value="{{$task->name}}" class="{{$classTask}} {{$hidden??"hidden"}}" onblur="onClickToCommit({{$task->id}},'lbl_task','txt_task','caption_task', route_task)">{{$task->name}}</textarea>
        </div>
        <div>
            @if($task->assignee_1)
                <x-renderer.avatar-user uid="{{$task->assignee_1}}" icon="45"/>
            @endif
        </div>
    </div>
    @switch($task->task_priority)
        @case(366)
            {{-- <span class="text-green-600" title="Very Low">▼</span> --}}
            <i class="fa-duotone fa-chevrons-down text-green-600" title="Very Low"></i>
        @break
        @case(367)
            {{-- <span class="text-green-300" title="Low">▼</span> --}}
            <i class="fa-duotone fa-chevron-down text-green-600" title="Low"></i>
        @break
        @case(368)
            <span class="text-blue-500" title="Medium">—</span>
        @break
        @case(369)
            {{-- <span class="text-red-300" title="High">▲</span> --}}
            <i class="fa-duotone fa-chevron-up text-red-600" title="High"></i>
        @break
        @case(370)
            {{-- <span class="text-red-600" title="Very High">▲</span> --}}
            <i class="fa-duotone fa-chevrons-up text-red-600" title="Very High"></i>
        @break
    @endswitch
    
    @if($task->target_hours)
    Target hour: {{$task->target_hours}}
    @endif
    <hr class=""/>
    <div class="flex justify-between pt-1">
        <div id="taskElapseTxt_{{$task->id}}"></div>
        <button class="fa-duotone fa-ellipsis {{App\Utils\ClassList::BUTTON_KANBAN_ELLIPSIS}}" @click="toggleModal('{{$modalId}}', {id: {{$task->id}}})" @keydown.escape="closeModal('{{$modalId}}')" ></button>
    </div>
</div>

<script>
    intervalId = setInterval(() => taskInterval("{{$task->id}}"), 1000)
    $("#intervalId_{{$task->id}}").val(intervalId)
    globalInterval.push(intervalId)
</script>