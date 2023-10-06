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

<div class="px-4 mt-2" component="entity-view-all-kanban.blade">
    <x-elapse title="Bootrap: " duration="{{$frameworkTook}}"/>   
    <x-elapse title="ViewAllController: "/> 
    
    <div class="grid grid-cols-12 gap-2">
        <div id="divKanbanSidebar" class="col-span-12 md:col-span-4 lg:col-span-3 2xl:col-span-2">
            <x-renderer.kanban.buckets :page="$page" />
        </div>
        <div id="divKanbanPage" class="col-span-12 md:col-span-8 lg:col-span-9 2xl:col-span-10">
            <x-renderer.kanban.page :page="$page" groupWidth="{{$groupWidth}}"/>
        </div>
    </div>
    <x-renderer.kanban.modals groupWidth="{{$groupWidth}}"/>
</div>
@endsection
