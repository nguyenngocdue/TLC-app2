<x-renderer.card class="{{$class}}">
    <div id="tabs-{{$id}}" class="inline-flex pt-2 px-0 w-full border-b ">
        @forelse($tabs as $tab)

        <div id="tab-pan-{{$tab['id'] ?? ''}}" class="cursor-pointer px-2.5 py-1.5 text-gray-800 mx-0.5 rounded-t border-t border-r border-l {{$tab['class'] ?? ""}}"
            @if(isset($tab['jsOnMouseOver']))
            onmouseover="{!! $tab['jsOnMouseOver'] !!}"
            @endif
            >
            <a href="{{$tab['href'] ?? "#"}}">{!! $tab['title'] !!}</a>
        </div>
        @empty
        <x-feedback.alert message="TabPane array is empty." type="warning"></x-feedback.alert>
        @endforelse
    </div>
    <div class="border border-t-0 rounded-b">
        {{$slot}}
    </div>
</x-renderer.card>