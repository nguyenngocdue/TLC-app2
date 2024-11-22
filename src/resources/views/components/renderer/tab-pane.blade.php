<x-renderer.card class="{{$class}}">
    <div id="tabs-{{$id}}" class="inline-flex px-0 w-full border-b h-10 mt-2 ">
        @forelse($tabs as $tab)

            <span id="tab-pan-{{$tab['id'] ?? ''}}" 
            class="tab-pan px-2.5 py-1.5 mx-0.5 rounded-t border-t border-r border-l {{$tab['class'] ?? ''}}"
            onmouseover="{{$tab['jsOnMouseOver'] ?? ''}}"                
            >
                @if(isset($tab['href'])) <a href="{{$tab['href']}}" class="cursor-pointer"> @endif
                    {!! $tab['title'] !!}
                @if(isset($tab['href'])) </a> @endif
            </span>

        @empty
            <x-feedback.alert message="TabPane array is empty." type="warning"></x-feedback.alert>
        @endforelse
    </div>
    <div class="border border-t-0 rounded-b bg-{{$activeBgColor}}-200">
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