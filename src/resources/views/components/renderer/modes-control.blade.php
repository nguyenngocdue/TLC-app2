{{-- @dump($dataSource) --}}
<form class="w-full no-print flex flex-row-reverse   rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3 ">
    <div class="grid grid-rows-1 ">
        <div class="grid grid-cols-12 items-center" style="display: flex">
            @foreach($dataSource as $name =>$value)
            @php
            $show = in_array($name, $hiddenItems) ? "hidden" : "";
            @endphp
            @if($name !== "No")
            <div class="col-span-8 w-full {{$show}}">
                <x-renderer.dropdown name="{{$name}}" :dataSource="$value" :itemsSelected="$itemsSelected" :hiddenItems="$hiddenItems" />
            </div>
            @endif
            @endforeach
            <div class="col-span-2 ">
                <button type="submit" class="px-4  py-2  inline-block font-medium text-sm leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-purple-600 text-white shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none active:bg-purple-800 active:shadow-lg">
                    Submit
                </button>
            </div>
        </div>
    </div>

</form>
