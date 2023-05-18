<div id="{{$id}}" class="inline-flex pt-2 px-1 w-full border-b">
    @foreach($dataSource as $tab)
    <div class="px-2 py-1 text-gray-800 font-semibold mx-0.5 rounded-t border-t border-r border-l {{$tab['class'] ?? ""}}">
        <a href="{{$tab['href']}}">{!! $tab['title'] !!}</a>
    </div>
    @endforeach
</div>
<div class="bg-white" component="tab_content">
    <x-renderer.emptiness />
    <x-renderer.emptiness />
    <x-renderer.emptiness />
    <x-renderer.emptiness />
</div>
