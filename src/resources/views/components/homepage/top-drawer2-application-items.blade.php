
<div id="topDrawerGroup_{{$item['id']}}" class="hidden px-4 top-drawer-group w-full sm:w-3/4">
    <div class="grid grid-cols-12 application-items">
        @forelse($item["items"] as $item)
            <x-homepage.top-drawer2-application-item :item="$item" />
        @empty
            <div class="col-span-12 p-2 border w-full rounded mx-auto text-center">
                There is no items under this group
            </div>
        @endforelse
    </div>
</div>