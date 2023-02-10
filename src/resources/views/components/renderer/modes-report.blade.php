{{-- @dump($nameControl1, $nameControl2) --}}
<form class="w-full mb-8 rounded-lg  dark:bg-gray-800">
    <div class="grid grid-row-1 justify-end">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-4 w-full">
                <x-renderer.dropdown name="{{$nameControl1}}" :dataSource="$dataControl1" :itemsSelected="$itemsSelected"></x-renderer.dropdown>
            </div>
            <div class="col-span-4 w-full">
                <x-renderer.dropdown name="{{$nameControl2}}" :dataSource="$dataControl2" :itemsSelected="$itemsSelected"></x-renderer.dropdown>
            </div>
            <div class="col-span-4 w-full">
                <button type="submit" class="focus:shadow-outline rounded bg-emerald-500 h-full px-4 font-bold text-white hover:bg-purple-400 focus:outline-none">
                    Update
                </button>
            </div>
        </div>
    </div>
    </div>
</form>
