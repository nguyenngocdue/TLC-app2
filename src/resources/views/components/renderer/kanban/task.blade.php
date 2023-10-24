@php 
 $classTask = "bg-white p-1 shadow rounded text-xs w-full focus:border-1 bold my1-1";
 $modalId = "modal-task";
 $title = "$task->description\n(#{$task->id})";
//  dump($group->time_counting_type);
//  $class = $group->time_counting_type === "0" ? "hidden" : "";
@endphp

<div id="task_parent_{{$task->id}}" data-id="task_{{$task->id}}" class="bg-white p-2 shadow rounded text-xs my1-1 flex justify-between" >
    <div class="w-full">
        <h2 id="lbl_task_{{$task->id}}" class="cursor-grab" >
            <span id="caption_task_{{$task->id}}" title="{{$title}}" onclick="onClickToEdit({{$task->id}},'lbl_task', 'txt_task')" class="cursor-pointer">{{$task->name ?? "???"}} </span>
            <button class="fa-duotone fa-ellipsis {{App\Utils\ClassList::BUTTON_KANBAN_ELLIPSIS}}" @click="toggleModal('{{$modalId}}', {id: {{$task->id}}})" @keydown.escape="closeModal('{{$modalId}}')" ></button>
        </h2>

        <input id="taskParentId_{{$task->id}}" class="bg-gray-300 rounded px-2" type="{{$hidden??"hidden"}}" value="{{$task->kanban_group_id}}"/>
        <input id="taskParentTimeCountingType_{{$task->id}}" class="bg-gray-300 rounded px-2" type="{{$hidden??"hidden"}}" value="{{$group->time_counting_type}}"/>
        <input id="taskParentPreviousGroupId_{{$task->id}}" class="bg-gray-300 rounded px-2" type="{{$hidden??"hidden"}}" value="{{$group->previous_group_id}}"/>
        <input id="intervalId_{{$task->id}}" class="bg-gray-300 rounded px-2" type="{{$hidden??"hidden"}}"/>
        
        <div id="taskElapseTxt_{{$task->id}}"></div>
        <textarea id="txt_task_{{$task->id}}" value="{{$task->name}}" class="{{$classTask}} {{$hidden??"hidden"}}" onblur="onClickToCommit({{$task->id}},'lbl_task','txt_task','caption_task', route_task)">{{$task->name}}</textarea>
    </div>
    <div>
        @if($task->assignee_1)
            <x-renderer.avatar-user uid="{{$task->assignee_1}}" icon="45"/>
        @endif
    </div>
</div>

<script>
    intervalId = setInterval(() => taskInterval("{{$task->id}}"), 1000)
    $("#intervalId_{{$task->id}}").val(intervalId)
    globalInterval.push(intervalId)
</script>