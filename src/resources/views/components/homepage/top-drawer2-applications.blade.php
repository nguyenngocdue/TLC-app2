<div id="topDrawer2Applications" class="p-1">
    <div class="grid grid-cols-12 gap-1 h-full">
        <div class="col-span-12 sm:col-span-4 md:col-span-3 xl:col-span-2 space-y-1 ">
            @forelse($dataSource as $value)
            <div 
                class="p-2 border bg-blue-50 hover:bg-blue-200 hover:font-bold hover:text-blue-600 cursor-pointer rounded flex justify-between"
                @if(MobileDetect::isMobile())
                    onclick="toggleTopDrawer2GroupOnPhone('{{$value['id']}}')"
                @else
                    onmouseover="toggleTopDrawer2Group('{{$value['id']}}')"
                @endif
                >
                <span>{{$value['title']}}</span>
                <i class="fa-duotone fa-caret-down sm:hidden"></i>
                <i class="fa-duotone fa-caret-right hidden sm:block"></i>
            </div>
            {{-- For Mobile --}}
            <div class="sm:hidden">
                <x-homepage.top-drawer2-application-items :item="$value"></x-homepage.top-drawer2-application-items>
            </div>                
            @empty
                ?????
            @endforelse
        </div>
        {{-- For PC --}}
        <div class="hidden sm:grid sm:col-span-8 xl:col-span-10">
            @forelse($dataSource as $value) 
                <x-homepage.top-drawer2-application-items :item="$value"></x-homepage.top-drawer2-application-items>
            @empty
                ?????
            @endforelse
        </div>
        {{-- SOME CSS ISSUE, THIS IS ALWAYS HIDDEN --}}
        {{-- <div class="hidden sm:col-span-4 p-1">
            <div id="divContentDescription" class="hidden1 bg-red-400 border rounded p-2">
                Content
            </div>
        </div>             --}}
    </div>
</div>

<script>
var currentTopDrawer2Group = null;
function toggleTopDrawer2Group(current){
    if(currentTopDrawer2Group === current) return;
    currentTopDrawer2Group = current;
        
    let topDrawerGroups = document.querySelectorAll('.top-drawer-group');
    topDrawerGroups.forEach((group) => {
        if(group.id === 'topDrawerGroup_' + current){
            group.classList.remove('hidden');
        }else{
            group.classList.add('hidden');
        }
    });
}

function toggleTopDrawer2GroupOnPhone(current){    
    if(currentTopDrawer2Group === current){
        let topDrawerGroups = document.querySelectorAll('.top-drawer-group');
        topDrawerGroups.forEach((group) => {
            group.classList.add('hidden');
        });
        currentTopDrawer2Group = null;
    } else {
        toggleTopDrawer2Group(current);
    }
}
</script>