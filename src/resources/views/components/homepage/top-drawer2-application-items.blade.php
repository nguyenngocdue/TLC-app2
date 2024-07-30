
<div id="topDrawerGroup_{{$item['id']}}" class="hidden px-4 top-drawer-group w-full sm:w-3/4">
    <div class="grid grid-cols-12 application-items">
        @foreach($item["items"] as $value)
            @if(!$value["hidden_for_non_admin"]) @continue @endif
            @if(!$value["hidden_navbar"]) @continue @endif
            <x-homepage.top-drawer2-application-item :item="$value" />
        @endforeach
    </div>
</div>