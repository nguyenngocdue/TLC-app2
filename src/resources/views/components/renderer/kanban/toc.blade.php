@php
    $classPage = "bg-gray-2001 p-1 shadow rounded text-xs w-full focus:border-1 bold my1-1";
    $modalId = "modal-page";
@endphp

{{-- @dump($pageId, $page->id) --}}

<div id="toc_{{$page->id}}" data-id="toc_{{$page->id}}" class="bg-blue-200 p-2 shadow rounded text-xs my1-1 cursor-grab" >
    <div class="flex justify-between">
        <div >
            <h2 id="lbl_toc_{{$page->id}}" title="page {{$page->id}}" >
                <span id="caption_toc_{{$page->id}}" onclick="onClickToEdit({{$page->id}},'lbl_toc', 'txt_toc')" class="cursor-pointer">{{$page->name ?? "???"}} </span>
                <button class="fa-duotone fa-ellipsis {{App\Utils\ClassList::BUTTON_KANBAN_ELLIPSIS}}" @click="toggleModal('{{$modalId}}', {id: {{$page->id}}})" @keydown.escape="closeModal('{{$modalId}}')" ></button>
            </h2>
            <input id="txt_toc_{{$page->id}}" value="{{$page->name}}" class="{{$classPage}} {{$hidden??"hidden"}}" onblur="onClickToCommit({{$page->id}},'lbl_toc','txt_toc','caption_toc', route_page); renameCurrentPage({{$page->id}})">
        </div>
        <span class="cursor-pointer" onclick="kanbanLoadPage({{$page->id}}, route_page)">
            <span id="iconOpen_{{$page->id}}" class="{{($page->id == ($pageId??false))?'':'hidden'}}"><i class="fa-duotone fa-folder-open"></i></span>
            <span id="iconClose_{{$page->id}}" class="{{($page->id == ($pageId??false))?'hidden':''}}"><i class="fa-duotone fa-folder"></i></span>
        </span>
    </div>
</div> 