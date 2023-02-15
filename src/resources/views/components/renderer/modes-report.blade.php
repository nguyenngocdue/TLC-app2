{{-- @dd($itemsSelected) --}}
<div class="px-4 no-print">
    <form class="w-full rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3 ">
        <div class="grid grid-row-1 justify-end">
            <div class="flex grid-cols-12">
                @foreach($dataRender as $name =>$value)
                <div class="col-span-4 w-full">
                    <x-renderer.dropdown name="{{$name}}" :dataSource="$value" :itemsSelected="$itemsSelected"></x-renderer.dropdown>
                </div>
                @endforeach
                <div class="col-span-4 w-full">
                    <button type="submit" class="px-4 ml-4 focus:shadow-outline rounded bg-emerald-500 h-full font-bold text-white hover:bg-purple-400 focus:outline-none">
                        Update
                    </button>
                </div>
            </div>
        </div>
</div>
</form>
</div>
