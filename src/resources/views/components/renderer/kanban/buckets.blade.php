@php
    $classButton = "bg-gray-200 p-1 shadow rounded text-xs w-full focus:border-1 bold my1-1";
@endphp

<script>const route_bucket = "{{$routeBucket}}";</script>

<div>
    <x-renderer.card title="All Pages"> 
        <div id="toc_group_1" data-id="toc_group" class="grid gap-1">
            @foreach($buckets as $bucket)
                <x-renderer.kanban.bucket :bucket="$bucket" />
            @endforeach
        </div>
        <script>kanbanInit1("toc_group_", [1], route_bucket, "categoryGroup")</script>
        
        <input id="txtCurrentPage" type="hidden" value="{{$pageId}}"/>
    </x-renderer.card>
</div>