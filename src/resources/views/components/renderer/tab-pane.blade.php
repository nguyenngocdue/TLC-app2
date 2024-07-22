<x-renderer.card class="{{$class}}">
    <div id="tabs-{{$id}}" class="hidden sm:inline-flex pt-2 px-1 w-full border-b ">
        @forelse($tabs as $tab)
        <div class="px-2.5 py-1.5 text-gray-800 mx-0.5 rounded-t border-t border-r border-l {{$tab['class'] ?? ""}}">
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