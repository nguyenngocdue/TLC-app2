@php
    $classPage = "bg-gray-2001 p-1 shadow rounded text-xs w-full focus:border-1 bold my1-1";
    $modalId = "modal-page";
    $title = "$page->description\n(#{$page->id})";
    $isOwner = App\Utils\Support\CurrentUser::id() == $page->owner_id;
    $pageId = App\Utils\Support\CurrentUser::get()->settings['kanban_task_page'][App\Utils\Constant::VIEW_ALL]['current_page'] ?? null;
@endphp

{{-- @dump($pageId, $page->id) --}}

<div id="toc_parent_{{$page->id}}" data-id="toc_{{$page->id}}" class="bg-blue-200 p-2 shadow rounded text-xs my1-1 cursor-grab" >
    <div class="flex justify-between">
        <div >
            <h2 id="lbl_toc_{{$page->id}}" title="page {{$page->id}}" >
                <span class="rounded px-1 font-bold">
                    @if($isOwner)
                    <i class="fa-duotone fa-crown text-yellow-600" title="Owner of this page"></i>
                    @else
                    <i class="fa-duotone fa-user text-yellow-600" title="Member of this page"></i>
                    @endif
                </span>
                <span id="caption_toc_{{$page->id}}" title="{{$title}}" onclick="onClickToEdit({{$page->id}},'lbl_toc', 'txt_toc')" class="cursor-pointer">{{$page->name ?? "???"}} </span>
                <button class="fa-duotone fa-ellipsis {{App\Utils\ClassList::BUTTON_KANBAN_ELLIPSIS}}" @click="toggleModal('{{$modalId}}', {id: {{$page->id}}})" @keydown.escape="closeModal('{{$modalId}}')" ></button>
            </h2>
            <input id="txt_toc_{{$page->id}}" value="{{$page->name}}" class="{{$classPage}} {{$hidden??"hidden"}}" onblur="onClickToCommit({{$page->id}},'lbl_toc','txt_toc','caption_toc', route_page); renameCurrentPage({{$page->id}})">
        </div>
        <div>
            {{-- <span class="bg-red-600 text-white rounded px-1 font-bold">3</span> --}}
            <span class="cursor-pointer" onclick="kanbanLoadPage({{$page->id}}, route_page, '{{$groupWidth}}')">
                <span id="iconOpen_{{$page->id}}" class="{{($page->id == ($pageId??false))?'':'hidden'}} text-[14px]"><i class="fa-duotone fa-folder-open"></i></span>
                <span id="iconClose_{{$page->id}}" class="{{($page->id == ($pageId??false))?'hidden':''}} text-[14px]"><i class="fa-duotone fa-folder"></i></span>
            </span>
        </div>
    </div>
    <div class="flex ml-2 cursor-pointer">
        @php
        $members = $page->getMonitors1();
        $first_10_members = $members->slice(0,10);
        $rest_of_members = $members->slice(10);
        $rest_of_member_names = $rest_of_members->map(fn($u)=>$u->full_name." - ".$u->getPosition->name." (#".$u->id.")")->join("\n");

        @endphp
        @foreach($first_10_members as $u)
            <div class="-ml-2">
                <x-renderer.avatar-user uid='{{$u["id"]}}' icon="30"></x-renderer.avatar-user>
            </div>
        @endforeach

    </div>
    @if(!$rest_of_members->isEmpty())
    <div class="text-blue-800 cursor-pointer" title="{!! $rest_of_member_names !!}">+{{sizeof($rest_of_members)}} more</div>
    @endif
</div> 