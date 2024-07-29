<div class="col-span-6 md:col-span-4 xl:col-span-3 2xl:col-span-2 p-2">
    <a 
        href="#111" 
        class="relative" 
        style="top:14%; right: -85%;"
        >
        <div class="relative w-8 h-8 text-center rounded-full {{$item['bookmarked'] ? "hover:bg-pink-700" : "hover:bg-blue-700"}}">
            <i class="fa-duotone fa-bookmark text-2xl {{$item['bookmarked'] ? "text-blue-400" : "text-gray-200"}}"></i>
        </div>
    </a>
    <a href="{{$item['href']}}">
        <div class="cursor-pointer hover1:font-bold hover:text-blue-600">
            <div class="flex1 border rounded aspect-square hover:border-blue-200  hover:bg-blue-200">
                <div class="h-8"></div>
                <div class="text-5xl text-center m-auto" style="margin-top: 5%;">                        
                    {!! $item["icon"] !!}
                    <div class="text-sm h-8 my-1 px-1">{{$item["title"]}}</div>
                    @roleset('admin')
                        <div class="text-xs p-1">({{$item["click_count"]}})</div>
                    @endroleset
                </div>
                {{-- <div 
                    class="relative flex items-center justify-center text-xs text-white rounded-full bg-pink-700 w-8 h-6 font-bold" 
                    style="bottom:-13%; right: -90%;">
                    {{ $item["click_count"] }}
                </div> --}}
            </div>      
        </div>
    </a>
</div>