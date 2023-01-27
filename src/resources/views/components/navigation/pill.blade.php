<div class="flex flex-col flex-wrap m1b-4 space-y-2 md:flex-row md:items-end md:space-x-1">
    @foreach($links as $link)
    @php
        $className = ($link['disabled']) ? "opacity-50 cursor-not-allowed" : "active:bg-purple-600 hover:bg-purple-700 focus:shadow-outline-purple";
        $href = ($link['disabled']) ? "javascript:void(0)": $link['href'];
        @endphp
            <a href="{{$href}}" >
                <button class="px-4 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg focus:outline-none {{$className}}">
                    {{$link['title']}}
                </button>
            </a>
            @endforeach
</div>
<div class="flex flex-col flex-wrap m1b-4 space-y-2 md:flex-row md:items-end md:space-x-1">
    @foreach($links1 as $link)
    @php
        $className = ($link['disabled']) ? "opacity-50 cursor-not-allowed" : "active:bg-purple-600 hover:bg-purple-700 focus:shadow-outline-purple";
        $href = ($link['disabled']) ? "javascript:void(0)": $link['href'];
    @endphp
            <a href="{{$href}}" >
        <button class="px-4 py-3 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg focus:outline-none {{$className}}">
            {{$link['title']}}
        </button>
    </a>
    @endforeach
</div>