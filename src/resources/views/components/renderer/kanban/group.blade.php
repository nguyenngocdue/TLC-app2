@php
    $categoryGroup = 'kanban_group_id';
    $classGroup = "bg-white p-1 shadow rounded text-xs w-full focus:border-1 bold my-1";
    $classButton = "text-xs border border-gray-300 rounded hover:bg-blue-200 shadow";
    $modalId = "modal-group";
@endphp

@once
    <input type="hidden" id="category_group" value="{{$categoryGroup}}" />
@endonce

<div id="group_parent_{{$group->id}}" data-id="group_{{$group->id}}" class="m-1 bg-gray-200 p-2 rounded">
    <h2 id="lbl_group_{{$group->id}}" class="text-xs font-bold my-2 cursor-pointer">
        <span id="caption_group_{{$group->id}}" onclick="onClickToEdit({{$group->id}},'lbl_group', 'txt_group')">{{$group->name}}</span>
        <button class="fa-duotone fa-ellipsis {{App\Utils\ClassList::BUTTON_KANBAN_ELLIPSIS}}" @click="toggleModal('{{$modalId}}', {id: {{$group->id}}})" @keydown.escape="closeModal('{{$modalId}}')" ></button>
    </h2>
    <input id="txt_group_{{$group->id}}" value="{{$group->name}}" class="{{$classGroup}} {{$hidden??"hidden"}}" onblur="onClickToCommit({{$group->id}},'lbl_group','txt_group','caption_group', route_group)">
    <div id="group_{{$group->id}}" data-id="group_{{$group->id}}" class="grid gap-1 {{$groupWidth}}">
        @foreach($group->getTasks as $task)
            <x-renderer.kanban.task :task="$task" hidden="{{$hidden}}" groupWidth="{{$groupWidth}}"/>
        @endforeach
    </div>
    <script>kanbanInit1("group_", [ {{$group->id}} ], route_task, "{{$categoryGroup}}")</script>
    <button class="{{$classButton}} mt-2 {{$groupWidth}}" type="button" onclick="addANewKanbanObj('group_', {{$group->id}}, route_task, '{{$groupWidth}}')">+ Add a Task</button>
    
</div>

@once
    <x-modals.kanban.modal-group modalId='{{$modalId}}'/>
@endonce