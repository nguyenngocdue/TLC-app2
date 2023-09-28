@php
    $categoryPage = 'kanban_page_id';
    $classButton = "text-xs border border-gray-300 rounded hover:bg-blue-200";
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.17.0/Sortable.min.css">

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="{{ asset('js/kanban.js') }}"></script>
<script>const route_cluster = "{{$routeCluster}}";</script>
<script>const route_group = "{{$routeGroup}}";</script>
<script>const route_task = "{{$routeTask}}";</script>

<div class="container1 mx-auto my-8">
    <div class="overflow-x-auto whitespace-no-wrap">
        <div id="page_{{$pageId}}">
            <x-renderer.kanban.cluster :clusters="$clusters" hidden="{{$hidden}}" groupWidth="{{$groupWidth}}"/>
        </div>
        <script>kanbanInit1("page_", [{{$pageId}}], route_cluster, "{{$categoryPage}}")</script>
        <button class="{{$classButton}} px-4 ml-2" type="button" onclick="addANew({{$pageId}}, route_cluster)">+ Add a Cluster</button>
        <br/>
    </div>
</div>

@endsection