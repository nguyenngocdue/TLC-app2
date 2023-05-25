<x-renderer.card class="bg-gray-100 {{$class}}">
    <div id="tabs-{{$id}}" class="inline-flex pt-2 px-1 w-full border-b">
        @forelse($tabs as $tab)
        <div class="px-2.5 py-1.5 text-gray-800 font-semibold mx-0.5 rounded-t border-t border-r border-l {{$tab['class'] ?? ""}}">
            <a href="{{$tab['href'] ?? "#"}}">{!! $tab['title'] !!}</a>
        </div>
        @empty
        <x-feedback.alert message="TabPane array is empty." type="warning"></x-feedback.alert>
        @endforelse
    </div>
    {{$slot}}
</x-renderer.card>