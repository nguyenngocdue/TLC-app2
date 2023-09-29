@php 
 $classTask = "bg-white p-1 shadow rounded text-xs w-full focus:border-1 bold my1-1";
@endphp

<div id="task_{{$task->id}}" data-id="task_{{$task->id}}" class="bg-white p-2 shadow rounded text-xs my1-1" >
    <div>
        <h2 id="lbl_task_{{$task->id}}" title="Task {{$task->id}}" class="cursor-grab" >
            <span>#</span>
            <span id="caption_task_{{$task->id}}" onclick="onClickToEdit({{$task->id}},'lbl_task', 'txt_task')" class="cursor-pointer">{{$task->name ?? "???"}} </span>
        </h2>
        <input id="txt_task_{{$task->id}}" value="{{$task->name}}" class="{{$classTask}} {{$hidden??"hidden"}}" onblur="onClickToCommit({{$task->id}},'lbl_task','txt_task','caption_task', route_task)">
    </div>
</div>