@php 
 $classTask = "bg-white p-1 shadow rounded text-xs w-full focus:border-1 bold my1-1";
 $modalId = "modal-task";
 $title = "$task->description\n(#{$task->id})"
@endphp

<div id="task_parent_{{$task->id}}" data-id="task_{{$task->id}}" class="bg-white p-2 shadow rounded text-xs my1-1 flex justify-between" >
    <div class="w-full">
        <h2 id="lbl_task_{{$task->id}}" class="cursor-grab" >
            <span id="caption_task_{{$task->id}}" title="{{$title}}" onclick="onClickToEdit({{$task->id}},'lbl_task', 'txt_task')" class="cursor-pointer">{{$task->name ?? "???"}} </span>
            <button class="fa-duotone fa-ellipsis {{App\Utils\ClassList::BUTTON_KANBAN_ELLIPSIS}}" @click="toggleModal('{{$modalId}}', {id: {{$task->id}}})" @keydown.escape="closeModal('{{$modalId}}')" ></button>
        </h2>
        <input id="taskParentId_{{$task->id}}" type="{{$hidden??"hidden"}}" value="{{$task->kanban_group_id}}"/>
        <div>
            Elapsed: <span class="text-blue-600" id="elapse_{{$task->id}}"></span>
        </div>
        <textarea id="txt_task_{{$task->id}}" value="{{$task->name}}" class="{{$classTask}} {{$hidden??"hidden"}}" onblur="onClickToCommit({{$task->id}},'lbl_task','txt_task','caption_task', route_task)">{{$task->name}}</textarea>
    </div>
    <div>
        @if($task->assignee_1)
            <x-renderer.avatar-user uid="{{$task->assignee_1}}" icon="45"/>
        @endif
    </div>
</div>

@once
<script>
    const convertSecondsToTime = (seconds) => {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const remainingSeconds = seconds % 60;

        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;

    }

    const taskInterval = (taskId) => {
        const groupId = $("#taskParentId_" + taskId).val()
        if(undefined === currentElapsed[groupId]) currentElapsed[groupId]={}
        if(!currentElapsed[groupId][taskId]) currentElapsed[groupId][taskId] = 0
        currentElapsed[groupId][taskId]++
        // console.log(taskId, currentElapsed[taskId])
        const time = convertSecondsToTime(currentElapsed[groupId][taskId])
        $('#elapse_'+taskId).html(time)
    }
</script>
@endonce

<script>
    setInterval(() => taskInterval("{{$task->id}}"), 1000);
</script>