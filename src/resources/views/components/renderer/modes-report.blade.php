{{-- @dd($itemsSelected) --}}
<form class="w-full mb-8 rounded-lg  dark:bg-gray-800 no-print">
    <div class="grid grid-row-1 justify-end">
        <div class="grid grid-cols-12">
            @foreach($dataRender as $name =>$value)
            <div class="col-span-4 w-full">
                <x-renderer.dropdown name="{{$name}}" :dataSource="$value" :itemsSelected="$itemsSelected"></x-renderer.dropdown>
            </div>
            @endforeach
            <div class="col-span-4 w-full">
                <button type="submit" class="focus:shadow-outline rounded bg-emerald-500 h-full px-4 font-bold text-white hover:bg-purple-400 focus:outline-none">
                    Update
                </button>
            </div>
        </div>
    </div>
    </div>
</form>
