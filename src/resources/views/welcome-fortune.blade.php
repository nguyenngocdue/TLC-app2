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

@php
  $class_cluster = "bg-white p-1 shadow rounded text-xs w-full1 focus:border-1 bold my-1";
  $class_group = "bg-white p-1 shadow rounded text-xs w-full focus:border-1 bold my-1";
  $class_task = "bg-white p-1 shadow rounded text-xs w-full focus:border-1 bold my1-1";
@endphp

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
        <h2 id="lbl_cluster_{{$cluster->id}}"  onclick="onClickToEdit({{$cluster->id}},'lbl_cluster', 'txt_cluster')">
          <span title="Cluster {{$cluster->id}}" class="cursor-grab">#</span> 
          {{$cluster->name}}</h2>
        <input id="txt_cluster_{{$cluster->id}}" value="{{$cluster->name}}" class="{{$class_cluster}} {{$hidden}}" onblur="onClickToCommit({{$cluster->id}},'lbl_cluster','txt_cluster')">
        <div id="cluster_{{$cluster->id}}" class="flex bg-gray-50 m-2 border rounded p-2">
          <script>kanbanInit1("cluster_",  [{{$cluster->id}}], route_group, "{{$category_cluster}}")</script>
          @php $groups = $cluster->getGroups; @endphp
          @foreach($groups as $group)
          <div id="group_parent_{{$group->id}}" class="m-1 bg-gray-200 p-2 rounded">
            <h2 id="lbl_group_{{$group->id}}" class="text-xs font-bold my-2" onclick="onClickToEdit({{$group->id}},'lbl_group', 'txt_group')">
              <span title="Group {{$group->id}}" class="cursor-grab">#</span> {{$group->name}}</h2>
              <input id="txt_group_{{$group->id}}" value="{{$group->name}}" class="{{$class_group}} {{$hidden}}" onblur="onClickToCommit({{$group->id}},'lbl_group','txt_group')">
              <div id="group_{{$group->id}}" class="grid gap-1 w-60">
                <script>kanbanInit1("group_", [ {{$group->id}} ], route_task, "{{$category_group}}")</script>
                @foreach($group->getTasks as $task)
                <div id="task_{{$task->id}}" class="bg-white p-2 shadow rounded text-xs my1-1" >
                  <div onclick="onClickToEdit({{$task->id}},'lbl_task', 'txt_task')">
                    <span id="lbl_task_{{$task->id}}" title="Task {{$task->id}}" class="cursor-grab" >
                      # {{$task->name ?? "???"}} </span>
                      <input id="txt_task_{{$task->id}}" value="{{$task->name}}" class="{{$class_task}} {{$hidden}}" onblur="onClickToCommit({{$task->id}},'lbl_task','txt_task')">
                    </div>
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
    <br/>