@php
    $classButton = "bg-gray-200 p-1 shadow rounded text-xs w-full focus:border-1 bold my1-1";
@endphp

<script>const route_page = "{{$routePage}}";</script>

<div>
    <x-renderer.card title="All Pages"> 
        <div id="toc_group_1" data-id="toc_group" class="grid gap-1">
            @foreach($pages as $page)
                <x-renderer.kanban.toc :page="$page" pageId="{{$pageId}}"/>
            @endforeach
        </div>
        <script>kanbanInit1("toc_group_", [1], route_page, "categoryGroup")</script>
        <button class="{{$classButton}} mt-2 w-full" type="button" onclick="addANewKanbanObj('toc_group_', 1, route_page, 'no-class')">+ Add a Page</button>
        <input id="txtCurrentPage" type="hidden" value="{{$pageId}}"/>
    </x-renderer.card>
</div>