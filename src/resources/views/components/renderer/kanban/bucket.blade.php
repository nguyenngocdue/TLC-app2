@php
    $categoryBucket = 'kanban_bucket_id';
    $classBucket = "bg-white p-1 shadow rounded text-xs w-full focus:border-1 bold my-1";
    $classButton = "text-xs border border-gray-300 rounded hover:bg-blue-200 shadow";
    $modalId = "modal-bucket";
    $title = "$bucket->description\n(#{$bucket->id})"
@endphp

{{-- @once
    <input type="hidden" id="category_bucket" value="{{$categoryBucket}}" />
@endonce --}}

<div id="bucket_parent_{{$bucket->id}}" data-id="bucket_{{$bucket->id}}" class="m-1 b1g-gray-200 p-1 rounded">
    <div id="lbl_bucket_{{$bucket->id}}" class="flex text-xs font-bold mb-1 cursor-pointer justify-between">
        <div>
            <span id="caption_bucket_{{$bucket->id}}" title="{{$title}}" onclick="onClickToEdit({{$bucket->id}},'lbl_bucket', 'txt_bucket')">{{$bucket->name}}</span>
            <button class="fa-duotone fa-ellipsis {{App\Utils\ClassList::BUTTON_KANBAN_ELLIPSIS}}" @click="toggleModal('{{$modalId}}', {id: {{$bucket->id}}})" @keydown.escape="closeModal('{{$modalId}}')" ></button>
        </div>
        <div>
            <button class="{{$classButton}} px-2 whitespace-nowrap" type="button" onclick="addANewKanbanObj('bucket_', {{$bucket->id}}, route_page, '')">+ Page</button>
        </div>
    </div>
    <input id="txt_bucket_{{$bucket->id}}" value="{{$bucket->name}}" class="{{$classBucket}} {{$hidden??"hidden"}}" onblur="onClickToCommit({{$bucket->id}},'lbl_bucket','txt_bucket','caption_bucket', route_bucket)">
    <div id="bucket_{{$bucket->id}}" data-id="bucket_{{$bucket->id}}" class="grid gap-1 ">
        @foreach($bucket->getPages as $page)
            <x-renderer.kanban.toc :page="$page" hidden="{{$hidden??'hidden'}}" />
        @endforeach
    </div>
    <script>kanbanInit1("bucket_", [ {{$bucket->id}} ], route_page, "{{$categoryBucket}}")</script>
    
</div>