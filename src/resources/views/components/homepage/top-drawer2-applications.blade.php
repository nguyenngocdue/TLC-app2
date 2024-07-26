<div id="topDrawer2Applications" class="p-1">
    <div class="grid grid-cols-12 gap-1 h-full">
        <div class="col-span-12 sm:col-span-4 md:col-span-3 xl:col-span-2 space-y-1 ">
            @forelse($dataSource as $value)
            <div 
                class="p-2 border bg-blue-50 hover:bg-blue-200 hover:font-bold hover:text-blue-600 cursor-pointer rounded flex justify-between"
                onmouseover="toggleTopDrawer2Group('{{$value['id']}}')"
                {{-- onmouseout="currentTopDrawer2Group = null" --}}
                >
                <span>{{$value['title']}}</span>
                <i class="fa-duotone fa-caret-down sm:hidden"></i>
                <i class="fa-duotone fa-caret-right hidden sm:block"></i>
            </div>
            @empty
                ?????
            @endforelse
        </div>
        @forelse($dataSource as $value) 
        <div id="topDrawerGroup_{{$value['id']}}" class="hidden p-4 top-drawer-group col-span-12 sm:col-span-8 xl:col-span-10 mx-auto w-3/4 grid grid-cols-12 ">
            @forelse($value["items"] as $item)
                <div class="col-span-6 p-4 sm:col-span-6 md:col-span-4 xl:col-span-3 2xl:col-span-2 cursor-pointer hover:font-bold hover:text-blue-600">
                    <div class="border rounded  aspect-square flex hover:border-blue-400  hover:bg-blue-200">
                        <div class="text-5xl text-center m-auto">                        
                            {!! $item["icon"] !!}
                        </div>
                    </div>
                    <div class="relative text-center -mt-10">
                        {{$item["title"]}}
                    </div>
                </div>
            @empty
                <div class="col-span-12 p-2 border w-full rounded mx-auto text-center">
                    There is no items under this group
                </div>
            @endforelse
        </div>
        @empty
        @endforelse
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
</script>