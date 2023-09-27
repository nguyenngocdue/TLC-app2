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

  $group_width = "w-72"; //10, 20, 32, 40, 52, 60, 72, 80, 96,
@endphp

<input type="hidden" id="category_group" value="{{$category_group}}" />
<input type="hidden" id="category_cluster" value="{{$category_cluster}}" />
<div class="container1 mx-auto my-8">
  <div class="overflow-x-auto whitespace-no-wrap">
    
    <div id="page_1">
      @foreach($clusters as $cluster)
      <div id="cluster_parent_{{$cluster->id}}" data-id="cluster_{{$cluster->id}}">
        <h2 id="lbl_cluster_{{$cluster->id}}" class="cursor-pointer" onclick="onClickToEdit({{$cluster->id}},'lbl_cluster', 'txt_cluster')">
          <span title="Cluster {{$cluster->id}}" class="cursor-grab">#</span> 
          {{$cluster->name}}</h2>
        <input id="txt_cluster_{{$cluster->id}}" value="{{$cluster->name}}" class="{{$class_cluster}} {{$hidden}}" onblur="onClickToCommit({{$cluster->id}},'lbl_cluster','txt_cluster')">
        <div id="cluster_{{$cluster->id}}" data-id="cluster_{{$cluster->id}}" class="flex bg-gray-50 m-2 border rounded p-2">
          @php $groups = $cluster->getGroups; @endphp
          @foreach($groups as $group)
          <div id="group_parent_{{$group->id}}" data-id="group_{{$group->id}}" class="m-1 bg-gray-200 p-2 rounded">
            <h2 id="lbl_group_{{$group->id}}" class="text-xs font-bold my-2 cursor-pointer" onclick="onClickToEdit({{$group->id}},'lbl_group', 'txt_group')">
              <span title="Group {{$group->id}}" class="cursor-grab">#</span> {{$group->name}}</h2>
              <input id="txt_group_{{$group->id}}" value="{{$group->name}}" class="{{$class_group}} {{$hidden}}" onblur="onClickToCommit({{$group->id}},'lbl_group','txt_group')">
              <div id="group_{{$group->id}}" data-id="group_{{$group->id}}" class="grid gap-1 {{$group_width}}">
                @foreach($group->getTasks as $task)
                <div id="task_{{$task->id}}" data-id="task_{{$task->id}}" class="bg-white p-2 shadow rounded text-xs my1-1" >
                  <div onclick="onClickToEdit({{$task->id}},'lbl_task', 'txt_task')" class="cursor-pointer">
                    <span id="lbl_task_{{$task->id}}" title="Task {{$task->id}}" class="cursor-grab" >
                      # {{$task->name ?? "???"}} </span>
                      <input id="txt_task_{{$task->id}}" value="{{$task->name}}" class="{{$class_task}} {{$hidden}}" onblur="onClickToCommit({{$task->id}},'lbl_task','txt_task')">
                    </div>
                  </div>
                  @endforeach
                </div>
                <script>kanbanInit1("group_", [ {{$group->id}} ], route_task, "{{$category_group}}")</script>
                <button class="text-xs border border-gray-300 rounded hover:bg-blue-200 mt-2 {{$group_width}}">+ Add a Card</button>
              </div>
              @endforeach
            </div>
            <div class="m-1 bg-gray-200 p-2 rounded h-9">
              <script>kanbanInit1("cluster_",  [{{$cluster->id}}], route_group, "{{$category_cluster}}")</script>
              <button class="text-xs border border-gray-300 rounded hover:bg-blue-200 px-4 {{$group_width}}">+ Add a List</button>
            </div>
          </div>
          @endforeach
        </div>
        <script>kanbanInit1("page_", [1], route_cluster, "{{$category_page}}")</script>
        <button class="text-xs border border-gray-300 rounded hover:bg-blue-200 px-4 ml-2">+ Add a Cluster</button>
        <br/>
      </div>
    </div>
    
    @endsection
    <br/>