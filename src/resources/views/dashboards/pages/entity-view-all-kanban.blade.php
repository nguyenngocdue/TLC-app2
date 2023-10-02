@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', "View All")

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.17.0/Sortable.min.css">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="{{ asset('js/kanban.js') }}"></script>

<script>const route_cluster = "{{route('App\\Models\\Kanban_task_cluster'::getTableName() . '.kanban');}}";</script>
<script>const route_group = "{{route('App\\Models\\Kanban_task_group'::getTableName() . '.kanban')}}";</script>
<script>const route_task = "{{route('App\\Models\\Kanban_task'::getTableName() . '.kanban')}}";</script>
<script>const route_page = "{{route('App\\Models\\Kanban_task_page'::getTableName() . '.kanban')}}";</script>

<div class="px-4 mt-2">
    <x-elapse title="Bootrap: " duration="{{$frameworkTook}}"/>   
    <x-elapse title="ViewAllController: "/> 
    
    <div class="grid grid-cols-12 gap-2">
        <div class="col-span-2">
            <x-renderer.kanban.buckets :page="$page" />
        </div>
        <div id="divKanbanPage" class="col-span-10">
            <x-renderer.kanban.page :page="$page"/>
        </div>
    </div>
    <x-renderer.kanban.modals />
</div>
@endsection
