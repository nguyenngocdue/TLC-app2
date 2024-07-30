<div id="topDrawer2Applications" class="p-1">
    <div class="grid grid-cols-12 gap-1 h-full">
        <div class="col-span-12 sm:col-span-4 md:col-span-3 xl:col-span-2 space-y-1 ">
            @forelse($dataSource as $value)
            <div 
                class="p-2 border bg-blue-50 hover:bg-blue-200 hover1:font-bold hover:text-blue-600 cursor-pointer rounded flex justify-between"
                @if(MobileDetect::isMobile())
                    onclick="toggleTopDrawer2GroupOnPhone('{{$value['id']}}','phone')"
                @else
                    onmouseover="toggleTopDrawer2Group('{{$value['id']}}','pc')"
                @endif
                >
                <div>
                    {{$value['title']}}                   
                </div>
                <i class="fa-duotone fa-caret-down sm:hidden"></i>
                <i class="fa-duotone fa-caret-right hidden sm:block"></i>
            </div>
            {{-- For Mobile --}}
            <div id="divTopDrawerForPhone_{{$value['id']}}" class="sm:hidden top-drawer-group-phone"></div>                
            @empty
                There is no application item to display.
            @endforelse
        </div>
        {{-- For PC --}}
        <div id="divTopDrawerForPc" class="hidden sm:grid sm:col-span-8 xl:col-span-10"></div>
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
function toggleTopDrawer2Group(current, device){
    if(currentTopDrawer2Group === current) return;
    currentTopDrawer2Group = current;

    switch(device){
        case 'pc': 
            topDrawerRenderer(current, 'divTopDrawerForPc', '{{$route}}');
            break;
        case 'phone': 
            let topDrawerGroups = document.querySelectorAll('.top-drawer-group-phone');
            topDrawerGroups.forEach((group) => {
                if(group.id === 'divTopDrawerForPhone_' + current){
                    group.classList.remove('hidden');
                }else{
                    group.classList.add('hidden');
                }
            });
            topDrawerRenderer(current, 'divTopDrawerForPhone_' + current, '{{$route}}');
            break;
    }
}

function toggleTopDrawer2GroupOnPhone(current, device){   
    if(currentTopDrawer2Group === current){
        let topDrawerGroups = document.querySelectorAll('.top-drawer-group-phone');
        topDrawerGroups.forEach((group) => group.classList.add('hidden'));
        currentTopDrawer2Group = null;
    } else {
        toggleTopDrawer2Group(current, device);
    }
}
</script>