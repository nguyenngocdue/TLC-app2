<form class="w-full no-print rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3 ">
    <div class="grid grid-row-1 justify-end">
        <div class="grid-cols-12 gap-4 flex  ">
            @foreach($dataRender as $name =>$value)
            <div class="col-span-3 w-[500px]">
                <div class="flex flex-nowrap items-center whitespace-nowrap">
                    <span class="px-4">{{ucwords(str_replace('_', " ", $name ))}}:</span>
                    <x-renderer.dropdown name="{{$name}}" :dataSource="$value" :itemsSelected="$itemsSelected"></x-renderer.dropdown>
                </div>
            </div>
            @endforeach
            <div class="">
                <button type="submit" class="px-4 ml-4 focus:shadow-outline rounded bg-emerald-500 h-full font-bold text-white hover:bg-purple-400 focus:outline-none">
                    Update
                </button>
            </div>
        </div>
    </div>
</form>
