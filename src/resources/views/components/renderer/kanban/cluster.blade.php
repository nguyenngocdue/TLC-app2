@php
    $categoryCluster = 'kanban_cluster_id';
    $classCluster = "bg-white p-1 shadow rounded text-xs w-full1 focus:border-1 bold my-1";
    $classButton = "text-xs border border-gray-300 rounded hover:bg-blue-200";
@endphp

@once
    <input type="hidden" id="category_cluster" value="{{$categoryCluster}}" />
@endonce

<div id="cluster_parent_{{$cluster->id}}" data-id="cluster_{{$cluster->id}}">
    <h2 id="lbl_cluster_{{$cluster->id}}" class="cursor-pointer" onclick="onClickToEdit({{$cluster->id}},'lbl_cluster', 'txt_cluster')">
        <span title="Cluster {{$cluster->id}}" class="cursor-grab">#</span> 
        <span id="caption_cluster_{{$cluster->id}}">{{$cluster->name}}</span>
    </h2>
    <input id="txt_cluster_{{$cluster->id}}" value="{{$cluster->name}}" class="{{$classCluster}} {{$hidden??"hidden"}}" onblur="onClickToCommit({{$cluster->id}},'lbl_cluster','txt_cluster','caption_cluster', route_cluster)">
    <div id="cluster_{{$cluster->id}}" data-id="cluster_{{$cluster->id}}" class="flex bg-gray-50 m-2 border rounded p-2">
        @php $groups = $cluster->getGroups; @endphp
        @foreach($groups as $group)
            <x-renderer.kanban.group :group="$group" hidden="{{$hidden}}" groupWidth="{{$groupWidth}}"/>
        @endforeach
        <div class="m-1 bg-gray-200 p-2 rounded h-9">
            <script>kanbanInit1("cluster_",  [{{$cluster->id}}], route_group, "{{$categoryCluster}}")</script>
            <button class="{{$classButton}} px-4 {{$groupWidth}}" type="button" onclick="addANewKanbanObj('cluster_', {{$cluster->id}}, route_group, '{{$groupWidth}}')">+ Add a List</button>
        </div>
    </div>
</div>