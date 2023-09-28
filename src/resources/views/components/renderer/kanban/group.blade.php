@php
    $categoryGroup = 'kanban_group_id';
    $classGroup = "bg-white p-1 shadow rounded text-xs w-full focus:border-1 bold my-1";
    $classButton = "text-xs border border-gray-300 rounded hover:bg-blue-200";
@endphp

@once
    <input type="hidden" id="category_group" value="{{$categoryGroup}}" />
@endonce

<div id="group_parent_{{$group->id}}" data-id="group_{{$group->id}}" class="m-1 bg-gray-200 p-2 rounded">
    <h2 id="lbl_group_{{$group->id}}" class="text-xs font-bold my-2 cursor-pointer" onclick="onClickToEdit({{$group->id}},'lbl_group', 'txt_group')">
        <span title="Group {{$group->id}}" class="cursor-grab">#</span> 
        <span id="caption_group_{{$group->id}}">{{$group->name}}</span>
    </h2>
    <input id="txt_group_{{$group->id}}" value="{{$group->name}}" class="{{$classGroup}} {{$hidden??"hidden"}}" onblur="onClickToCommit({{$group->id}},'lbl_group','txt_group','caption_group', route_group)">
    <div id="group_{{$group->id}}" data-id="group_{{$group->id}}" class="grid gap-1 {{$groupWidth}}">
        @foreach($group->getTasks as $task)
            <x-renderer.kanban.task :task="$task" hidden="{{$hidden}}" groupWidth="{{$groupWidth}}"/>
        @endforeach
    </div>
    <script>kanbanInit1("group_", [ {{$group->id}} ], route_task, "{{$categoryGroup}}")</script>
    <button class="{{$classButton}} mt-2 {{$groupWidth}}" type="button" onclick="addANewKanbanObj('group_', {{$group->id}}, route_task, '{{$groupWidth}}')">+ Add a Card</button>
    
</div>