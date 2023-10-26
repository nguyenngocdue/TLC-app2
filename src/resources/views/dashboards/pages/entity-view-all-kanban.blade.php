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
            <x-renderer.kanban.buckets :page="$page" groupWidth="{{$groupWidth}}"/>
        </div>
        <div id="divKanbanPage" class="col-span-12 md:col-span-8 lg:col-span-9 2xl:col-span-10">
            <x-renderer.kanban.page :page="$page" groupWidth="{{$groupWidth}}"/>
        </div>
    </div>
    <x-renderer.kanban.modals groupWidth="{{$groupWidth}}"/>
</div>

<script>
const getRouteByTable = {
    "kanban_tasks": route_task,
    "kanban_task_groups": route_group,
    "kanban_task_clusters": route_cluster,
    "kanban_task_pages": route_page,
    "kanban_task_buckets": route_bucket,
}
const getPrefix = {
    "kanban_tasks": "task_parent_",
    "kanban_task_groups": "group_parent_",
    "kanban_task_clusters": "cluster_parent_",
    "kanban_task_pagescardPage": "cardPage000",
    "kanban_task_pages": "toc_parent_",
    "kanban_task_buckets": "bucket_parent_",
}

const reRenderItem = (table, id, guiType='') => {
    const prefix = getPrefix[table + guiType] ?? "Unknown prefix of "+ table+guiType
    const url = getRouteByTable[table] ?? "Unknown route of "+ table
    // console.log("replacing", table, id, guiType, "with new renderer.")
    $.ajax({
        method: "POST",
        url,
        data: { action: "reRenderKanbanItem", id, table, guiType },
        success: function (response) {
            const { renderer } = response.hits
            kanbanReRender(table, prefix, id, guiType, renderer)
        },
        error: onKanbanAjaxError,
    })
}

const app_env = '{{env('APP_DOMAIN')}}';
window.Echo.channel('wss-kanban-channel-' + app_env).listen('WssKanbanChannel', (e) => {
    wsClientId1 = e.data.wsClientId
    if(wsClientId == wsClientId1) return //<<Ignore current tab.
    switch(e.data.action){
        case "changeOrder":
            const { parentType, parentId, guiType } = e.data
            reRenderItem(parentType, parentId, guiType)
            break;
        case "updateItemRenderProps":
        case "changeName":
            const {tableName, id} = e.data
            reRenderItem(tableName, id)
            break;
        case "addANewItem":
            const parent_type = e.data.parentType
            const parent_id = e.data.parent_id
            reRenderItem(parent_type, parent_id)
            break;
        case "deleteItemRenderProps":
            kanbanDeleteItemGui(e.data.prefix, e.data.id )
            break;
        case "changeParent":
            //It will have no impact onto any other clients
            break;
        default: 
            console.log("Unknown how to deal with action [",e.data.action,"]")
            console.log("WssKanbanChannel", e.data, wsClientId1);
            break;
    }
})
</script>

@endsection
