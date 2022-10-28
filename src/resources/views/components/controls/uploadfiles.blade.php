<div class="flex flex-col">
    @if ($action === "edit")

    <div class="flex  pb-3">
        @foreach($infMedia as $key => $media)
        {{-- {{dd($media, $cateIdName, $colName, $cateIdName[[$media['category']][0]])}} --}}
        @if ($colName === $cateIdName[[$media['category']][0]])
        <div class=" relative w-[25%] h-full flex mx-1 flex-col items-center p-1 border rounded-lg border-gray-300 group/item overflow-hidden  ">
            <a href={{ $path.$media['url_media'] }}>
                <img class="border  border-gray-300 rounded-md h-full w-full object-cover hover:bg-slate-100" src="{{ $path.$media['url_thumbnail']}}" alt="{{$media['filename']}}" />
            </a>
            <div class=" invisible hover:bg-[#00000080] group-hover/item:visible   before:absolute before:-inset-1  before:bg-[#00000080]">
                <span class="px-2 absolute  top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] text-white text-xs text-center w-full">{{$media['filename']}}
                    <button type="button">
                        <i class=" absolute bottom-[10%] fas fa-trash text-white cursor-pointer"></i>
                    </button>
                </span>
            </div>
        </div>
        @endif
        @endforeach
    </div>

    <input multiple class="  block w-full text-sm text-gray-900  p-1 rounded-lg bg-white border  border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 " id="multiple_files" type="file" name="{{$colName}}[]">

    @else
    <input multiple class=" block w-full text-sm text-gray-900 p-1 rounded-lg bg-white border  border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 " id="multiple_files" type="file" name="{{$colName}}[]">
    @endif
</div>
