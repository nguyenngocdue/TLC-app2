@php
    $categoryBucket = 'kanban_bucket_id';
    $classBucket = "bg-white p-1 shadow rounded text-xs w-full focus:border-1 bold my-1";
    $classButton = "text-xs border border-gray-300 rounded hover:bg-blue-200 shadow";
@endphp

{{-- @once
    <input type="hidden" id="category_bucket" value="{{$categoryBucket}}" />
@endonce --}}

<div id="bucket_parent_{{$bucket->id}}" data-id="bucket_{{$bucket->id}}" class="m-1 b1g-gray-200 p1-2 rounded">
    <h2 id="lbl_bucket_{{$bucket->id}}" class="text-xs font-bold my-2 cursor-pointer" onclick="onClickToEdit({{$bucket->id}},'lbl_bucket', 'txt_bucket')">
        <span title="Bucket {{$bucket->id}}" class="cursor-grab">#</span> 
        <span id="caption_bucket_{{$bucket->id}}">{{$bucket->name}}</span>
    </h2>
    <input id="txt_bucket_{{$bucket->id}}" value="{{$bucket->name}}" class="{{$classBucket}} {{$hidden??"hidden"}}" onblur="onClickToCommit({{$bucket->id}},'lbl_bucket','txt_bucket','caption_bucket', route_bucket)">
    <div id="bucket_{{$bucket->id}}" data-id="bucket_{{$bucket->id}}" class="grid gap-1 ">
        @foreach($bucket->getPages as $page)
            <x-renderer.kanban.toc :page="$page" pageId="{{$pageId}}" hidden="{{$hidden??'hidden'}}" />
        @endforeach
    </div>
    <script>kanbanInit1("bucket_", [ {{$bucket->id}} ], route_page, "{{$categoryBucket}}")</script>
    <button class="{{$classButton}} mt-2 " type="button" onclick="addANewKanbanObj('bucket_', {{$bucket->id}}, route_page, '')">+ Add a Page</button>
    
</div>