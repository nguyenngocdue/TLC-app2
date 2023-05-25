<div class="rounded border w-full mx-auto mt-4">
    <!-- Tabs -->
    <ul id="tabs-{{$tabId}}" class="inline-flex pt-2 px-1 w-full border-b">
        @foreach($tabs as $tab)
        @php
        $className = ($tab['key'] === $defaultTabKey) ? "bg-white border-t border-r border-l -mb-px" : "";
        @endphp
        <li class="px-4 text-gray-800 font-semibold py-2 rounded-t {{$className}}"><a href="#{{$tab['key']}}">{{$tab['label']}}</a></li>
        @endforeach
    </ul>

    <!-- Tab Contents -->
    <div id="tab-contents-{{$tabId}}">
        @foreach($tabs as $tab)
        @php
        $className = ($tab['key'] === $defaultTabKey) ? "" : "hidden";
        @endphp 
        <div id="{{$tab['key']}}" class="p-4 {{$className}}">
            {!! $tab['children'] !!}
        </div>
        @endforeach
    </div>
</div>

{{-- @once
<script type="text/javascript">
const initTab = (tabId) => {
    let tabsContainer = document.querySelector("#tabs-" + tabId);
    let tabTogglers = tabsContainer.querySelectorAll("#tabs-" + tabId + " a");
    tabTogglers.forEach(function(toggler) {
        toggler.addEventListener("click", function(e) {
            e.preventDefault();
            let tabName = this.getAttribute("href");
            let tabContents = document.querySelector("#tab-contents-" + tabId);
            for (let i = 0; i < tabContents.children.length; i++) {
                tabTogglers[i].parentElement.classList.remove("border-t", "border-r", "border-l", "-mb-px", "bg-white");
                tabContents.children[i].classList.remove("hidden");
                if ("#" + tabContents.children[i].id === tabName) continue;
                tabContents.children[i].classList.add("hidden");
            }
            e.target.parentElement.classList.add("border-t", "border-r", "border-l", "-mb-px", "bg-white");
        });
    });
}
</script>
@endonce

<script>initTab('{{$tabId}}');</script> --}}