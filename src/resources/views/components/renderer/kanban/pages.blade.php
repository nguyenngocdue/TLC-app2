@php 
 $classPage = "bg-gray-2001 p-1 shadow rounded text-xs w-full focus:border-1 bold my1-1";
@endphp

<script>const route_page = "{{$routePage}}";</script>

<div>
    <x-renderer.card title="All Pages"> 
        <div id="toc_group_1" data-id="toc_group" class="grid gap-1">
            @foreach($pages as $page)
            <div id="toc_{{$page->id}}" data-id="toc_{{$page->id}}" class="bg-blue-200 p-2 shadow rounded text-xs my1-1 cursor-grab" >
                <div class="flex justify-between">
                    <div >
                        <h2 id="lbl_toc_{{$page->id}}" title="page {{$page->id}}" >
                            {{-- <span>#</span> --}}
                            <span id="caption_toc_{{$page->id}}" onclick="onClickToEdit({{$page->id}},'lbl_toc', 'txt_toc')" class="cursor-pointer">{{$page->name ?? "???"}} </span>
                        </h2>
                        <input id="txt_toc_{{$page->id}}" value="{{$page->name}}" class="{{$classPage}} {{$hidden??"hidden"}}" onblur="onClickToCommit({{$page->id}},'lbl_toc','txt_toc','caption_toc', route_page); renameCurrentPage({{$page->id}})">
                    </div>
                    <span class="cursor-pointer" onclick="kanbanLoadPage({{$page->id}}, route_page, {{$pageIds}})">
                        <span id="iconOpen_{{$page->id}}" class="{{($page->id == $pageId)?'':'hidden'}}"><i class="fa-duotone fa-folder-open"></i></span>
                        <span id="iconClose_{{$page->id}}" class="{{($page->id == $pageId)?'hidden':''}}"><i class="fa-duotone fa-folder"></i></span>
                    </span>
                </div>
            </div> 
            @endforeach
        </div>
        <script>kanbanInit1("toc_group_", [1], route_page, "categoryGroup")</script>
        <input id="txtCurrentPage" type="hidden" value="{{$pageId}}"/>
    </x-renderer.card>
</div>