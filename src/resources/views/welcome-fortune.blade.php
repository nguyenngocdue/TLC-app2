@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.17.0/Sortable.min.css">

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="{{ asset('js/kanban.js') }}"></script>
<script>const route_cluster = "{{$route_cluster}}";</script>
<script>const route_group = "{{$route_group}}";</script>
<script>const route_task = "{{$route_task}}";</script>

<input type="hidden" id="category_group" value="{{$category_group}}" />
<input type="hidden" id="category_cluster" value="{{$category_cluster}}" />
<div class="container1 mx-auto my-8">
  <div class="overflow-x-auto whitespace-no-wrap">
    
    <div id="wrapper_0">
      <script>
        kanbanInit1("wrapper_", [0], route_cluster)
      </script>
      @foreach($clusters as $cluster)
      <div>
        <h2><span title="Cluster {{$cluster->id}}" class="cursor-grab">#</span> {{$cluster->name}}</h2>
          <div id="cluster_{{$cluster->id}}" class="flex bg-gray-50 m-2 border rounded p-2">
          <script>kanbanInit1("cluster_",  [{{$cluster->id}}], route_group, "{{$category_cluster}}")</script>
          @php $groups = $cluster->getGroups; @endphp
          @foreach($groups as $group)
          <div id="group_parent_{{$group->id}}" class="m-1 bg-gray-200 p-2 rounded">
            <h2 class="text-xs font-bold mb-1">
              <span title="Group {{$group->id }}" class="cursor-grab">#</span> {{$group->name}}</h2>
            <div id="group_{{$group->id}}" class="grid gap-1 w-60">
              <script>kanbanInit1("group_", [ {{$group->id}} ], route_task, "{{$category_group}}")</script>
              @foreach($group->getTasks as $task)
              <div id="task_{{$task->id}}" class="bg-white p-1 shadow rounded text-xs">
                <span title="Task {{$task->id}}" class="cursor-grab">#<span> {{$task->name ?? "???"}} 
              </div>
              <button class="text-xs border border-gray-300 rounded hover:bg-blue-200">+ Add a Card</button>
              @endforeach
            </div>
          </div>
          @endforeach
          <div class="m-1 bg-gray-200 p-2 rounded h-11">
            <button class="text-xs border border-gray-300 rounded hover:bg-blue-200 px-4">+ Add a List</button>
          </div>
        </div>
      </div>
      @endforeach
      <button class="text-xs border border-gray-300 rounded hover:bg-blue-200 px-4 ml-2">+ Add a Cluster</button>
    </div>
    <br/>
  </div>
</div>

@endsection