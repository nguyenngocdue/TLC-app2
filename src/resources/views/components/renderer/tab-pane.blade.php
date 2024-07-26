<x-renderer.card class="{{$class}}">
    <div id="tabs-{{$id}}" class="inline-flex pt-2 px-0 w-full border-b ">
        @forelse($tabs as $tab)

        <div id="tab-pan-{{$tab['id'] ?? ''}}" 
            class="tab-pan cursor-pointer px-2.5 py-1.5 text-gray-800 mx-0.5 rounded-t border-t border-r border-l {{$tab['class'] ?? ""}}"
            @if(isset($tab['jsOnMouseOver']))
                onmouseover="{{$tab['jsOnMouseOver']}}"
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

<script>
var currentTopDrawerTabPan = null;
function toggleTabPan(current){
    if(currentTopDrawerTabPan === current) return;
    currentTopDrawerTabPan = current;
    
    let tabPans = document.querySelectorAll('.tab-pan');
    tabPans.forEach((tabPan) => {
        if(tabPan.id === 'tab-pan-' + current){
            tabPan.classList.add('bg-white', '-mb-px');
            tabPan.classList.remove('bg-gray-200'); 
        }else{
            tabPan.classList.remove('bg-white', '-mb-px');
            tabPan.classList.add('bg-gray-200');
        }
    });
}
</script>